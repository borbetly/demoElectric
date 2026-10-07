<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCustomerAccounts extends Migration
{
    public function up()
    {
        if ($this->db->tableExists('customer_accounts')) {
            return;
        }

        // This intentionally mirrors the table and indexes in the supplied
        // electric_company SQL dump, while keeping the schema in this project.
        $this->db->query(<<<'SQL'
CREATE TABLE `customer_accounts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account_number` varchar(50) NOT NULL,
  `customer_name` varchar(150) NOT NULL,
  `address` text NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `meter_number` varchar(50) DEFAULT NULL,
  `connection_type` enum('residential','commercial','industrial') DEFAULT 'residential',
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `account_number` (`account_number`),
  KEY `idx_account_number` (`account_number`),
  KEY `idx_status` (`status`),
  KEY `idx_connection_type` (`connection_type`),
  KEY `idx_customer_name` (`customer_name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
SQL);
    }

    public function down()
    {
        $this->db->query('DROP TABLE IF EXISTS `customer_accounts`');
    }
}
