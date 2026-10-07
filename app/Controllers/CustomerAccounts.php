<?php

namespace App\Controllers;

use App\Models\CustomerAccountModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\RedirectResponse;
use Throwable;

class CustomerAccounts extends BaseController
{
    private CustomerAccountModel $customerAccounts;

    public function __construct()
    {
        $this->customerAccounts = new CustomerAccountModel();
    }

    public function index(): string
    {
        $search = $this->queryString('search');
        $status = $this->allowedValue($this->queryString('status'), ['active', 'inactive', 'suspended']);
        $type   = $this->allowedValue($this->queryString('type'), ['residential', 'commercial', 'industrial']);
        $counts = $this->customerAccounts->accountCounts();

        return view('accounts/index', [
            'title'         => 'Customer Accounts - Puihaha Electric',
            'page'          => 'accounts',
            'accounts'      => $this->customerAccounts->paginateAccounts($search, $status, $type),
            'pager'         => $this->customerAccounts->pager,
            'currentPage'   => $this->customerAccounts->pager->getCurrentPage('accounts'),
            'search'        => $search,
            'filterStatus'  => $status,
            'filterType'    => $type,
            'counts'        => $counts,
        ]);
    }

    public function show(int $id): string
    {
        return view('accounts/show', [
            'title'   => 'Account Details - Puihaha Electric',
            'page'    => 'accounts',
            'account' => $this->findAccount($id),
        ]);
    }

    public function new(): string
    {
        return view('accounts/form', [
            'title'       => 'Add Customer Account - Puihaha Electric',
            'page'        => 'accounts',
            'heading'     => 'Add Customer Account',
            'account'     => [],
            'formAction'  => route_to('accounts.create'),
            'submitLabel' => 'Create Account',
        ]);
    }

    public function create(): RedirectResponse
    {
        $data = $this->accountData();

        if (! $this->validateData($data, $this->rules())) {
            return $this->validationRedirect(route_to('accounts.new'));
        }

        try {
            if ($this->customerAccounts->insert($data) === false) {
                return redirect()->back()->withInput()
                    ->with('validation', $this->customerAccounts->errors())
                    ->with('error', 'The customer account could not be created.');
            }
        } catch (Throwable $exception) {
            log_message('error', 'Customer account creation failed: {message}', ['message' => $exception->getMessage()]);

            return redirect()->back()->withInput()->with('error', 'The customer account could not be created. Please try again.');
        }

        return redirect()->to(route_to('accounts.index'))->with('success', 'Customer account created successfully.');
    }

    public function edit(int $id): string
    {
        $account = $this->findAccount($id);

        return view('accounts/form', [
            'title'       => 'Edit Customer Account - Puihaha Electric',
            'page'        => 'accounts',
            'heading'     => 'Edit Customer Account',
            'account'     => $account,
            'formAction'  => route_to('accounts.update', $id),
            'submitLabel' => 'Save Changes',
        ]);
    }

    public function update(int $id): RedirectResponse
    {
        $this->findAccount($id);
        // The ID is used by the model's unique-account-number validation rule.
        // It is not in $allowedFields, so the update query cannot change it.
        $data = ['id' => $id] + $this->accountData();

        if (! $this->validateData($data, $this->rules($id))) {
            return $this->validationRedirect(route_to('accounts.edit', $id));
        }

        try {
            if (! $this->customerAccounts->update($id, $data)) {
                return redirect()->back()->withInput()
                    ->with('validation', $this->customerAccounts->errors())
                    ->with('error', 'The customer account could not be updated.');
            }
        } catch (Throwable $exception) {
            log_message('error', 'Customer account update failed: {message}', ['message' => $exception->getMessage()]);

            return redirect()->back()->withInput()->with('error', 'The customer account could not be updated. Please try again.');
        }

        return redirect()->to(route_to('accounts.show', $id))->with('success', 'Customer account updated successfully.');
    }

    public function delete(int $id): RedirectResponse
    {
        $this->findAccount($id);

        try {
            if (! $this->customerAccounts->delete($id)) {
                return redirect()->to(route_to('accounts.index'))->with('error', 'The customer account could not be deleted.');
            }
        } catch (Throwable $exception) {
            log_message('error', 'Customer account deletion failed: {message}', ['message' => $exception->getMessage()]);

            return redirect()->to(route_to('accounts.index'))->with('error', 'The customer account could not be deleted. Please try again.');
        }

        return redirect()->to(route_to('accounts.index'))->with('success', 'Customer account deleted successfully.');
    }

    /** @return array<string, string> */
    private function accountData(): array
    {
        return [
            'account_number'  => $this->postString('account_number'),
            'customer_name'   => $this->postString('customer_name'),
            'address'         => $this->postString('address'),
            'phone'           => $this->postString('phone'),
            'email'           => $this->postString('email'),
            'meter_number'    => $this->postString('meter_number'),
            'connection_type' => $this->postString('connection_type'),
            'status'          => $this->postString('status'),
        ];
    }

    /** @return array<string, string> */
    private function rules(?int $id = null): array
    {
        $uniqueAccountNumber = $id === null
            ? 'is_unique[customer_accounts.account_number]'
            : 'is_unique[customer_accounts.account_number,id,' . $id . ']';

        return [
            'account_number'  => 'required|max_length[50]|' . $uniqueAccountNumber,
            'customer_name'   => 'required|max_length[150]',
            'address'         => 'required',
            'phone'           => 'permit_empty|max_length[20]',
            'email'           => 'permit_empty|valid_email|max_length[100]',
            'meter_number'    => 'permit_empty|max_length[50]',
            'connection_type' => 'required|in_list[residential,commercial,industrial]',
            'status'          => 'required|in_list[active,inactive,suspended]',
        ];
    }

    private function findAccount(int $id): array
    {
        $account = $this->customerAccounts->find($id);

        if ($account === null) {
            throw PageNotFoundException::forPageNotFound('Customer account not found.');
        }

        return $account;
    }

    private function validationRedirect(string $route): RedirectResponse
    {
        return redirect()->to($route)->withInput()->with('validation', $this->validator->getErrors());
    }

    private function queryString(string $key): string
    {
        $value = $this->request->getGet($key);

        return is_string($value) ? trim($value) : '';
    }

    private function postString(string $key): string
    {
        $value = $this->request->getPost($key);

        return is_string($value) ? trim($value) : '';
    }

    /** @param list<string> $allowed */
    private function allowedValue(string $value, array $allowed): string
    {
        return in_array($value, $allowed, true) ? $value : '';
    }
}
