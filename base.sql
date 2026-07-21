-- base.sql
-- Structure de la base de données pour la simulation Mobile Money (Version 2)

PRAGMA foreign_keys = ON;

-- 1. Table des opérateurs
CREATE TABLE IF NOT EXISTS operators (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(100) UNIQUE,
    password_hash VARCHAR(255),
    name VARCHAR(100),
    code VARCHAR(50),
    is_main_operator BOOLEAN DEFAULT 0,
    is_active BOOLEAN DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 2. Table des préfixes téléphoniques
CREATE TABLE IF NOT EXISTS phone_prefixes (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    prefix VARCHAR(10) NOT NULL UNIQUE,
    operator_id INTEGER,
    is_active BOOLEAN NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operator_id) REFERENCES operators(id) ON DELETE SET NULL
);

-- 3. Table des types d'opérations
CREATE TABLE IF NOT EXISTS operation_types (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    code VARCHAR(50) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- 4. Table des tranches de frais
CREATE TABLE IF NOT EXISTS fee_brackets (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    operation_type_id INTEGER NOT NULL,
    min_amount DECIMAL(15, 2) NOT NULL,
    max_amount DECIMAL(15, 2) NOT NULL,
    fee_amount DECIMAL(15, 2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (operation_type_id) REFERENCES operation_types(id) ON DELETE CASCADE,
    CHECK (min_amount <= max_amount),
    CHECK (fee_amount >= 0)
);

-- 5. Table des commissions inter-opérateurs
CREATE TABLE IF NOT EXISTS operator_commissions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    source_operator_id INTEGER NOT NULL,
    destination_operator_id INTEGER NOT NULL,
    commission_percentage DECIMAL(5, 2) NOT NULL,
    is_active BOOLEAN DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (source_operator_id) REFERENCES operators(id) ON DELETE CASCADE,
    FOREIGN KEY (destination_operator_id) REFERENCES operators(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS solde_epargne (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    client_id INTEGER NOT NULL,
    epargne_percentage DECIMAL(5, 2) NOT NULL DEFAULT 0.0,
    total_amount DECIMAL(15, 2) DEFAULT 0.00,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE,
);

-- 6. Table des reversements (Settlements)
CREATE TABLE IF NOT EXISTS settlements (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    destination_operator_id INTEGER NOT NULL,
    period_start DATETIME NOT NULL,
    period_end DATETIME NOT NULL,
    total_transfer_amount DECIMAL(15, 2) DEFAULT 0.00,
    total_commission DECIMAL(15, 2) DEFAULT 0.00,
    amount_to_settle DECIMAL(15, 2) DEFAULT 0.00,
    amount_settled DECIMAL(15, 2) DEFAULT 0.00,
    status VARCHAR(50) DEFAULT 'PENDING',
    settled_at DATETIME,
    reference VARCHAR(100),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (destination_operator_id) REFERENCES operators(id) ON DELETE RESTRICT
);

-- 7. Table des clients
CREATE TABLE IF NOT EXISTS clients (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    phone_number VARCHAR(20) NOT NULL UNIQUE,
    balance DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    status VARCHAR(20) NOT NULL DEFAULT 'active', -- 'active', 'suspended'
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    CHECK (balance >= 0)
);

-- 8. Table des lots de transferts (Batches)
CREATE TABLE IF NOT EXISTS transfer_batches (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    sender_client_id INTEGER NOT NULL,
    total_amount DECIMAL(15, 2) DEFAULT 0.00,
    total_fee DECIMAL(15, 2) DEFAULT 0.00,
    total_commission DECIMAL(15, 2) DEFAULT 0.00,
    include_withdrawal_fee BOOLEAN DEFAULT 0,
    status VARCHAR(50) DEFAULT 'COMPLETED',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_client_id) REFERENCES clients(id) ON DELETE RESTRICT
);

-- 9. Table des transactions
CREATE TABLE IF NOT EXISTS transactions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    transaction_reference VARCHAR(50) NOT NULL UNIQUE,
    batch_id INTEGER,
    operation_type_id INTEGER NOT NULL,
    sender_client_id INTEGER,
    receiver_client_id INTEGER,
    destination_operator_id INTEGER,
    transfer_type VARCHAR(20) DEFAULT 'INTERNAL',
    amount DECIMAL(15, 2) NOT NULL,
    fee_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    commission_amount DECIMAL(15, 2) NOT NULL DEFAULT 0.00,
    total_amount DECIMAL(15, 2) NOT NULL,
    balance_before DECIMAL(15, 2) NOT NULL,
    balance_after DECIMAL(15, 2) NOT NULL,
    status VARCHAR(20) NOT NULL DEFAULT 'completed', -- 'pending', 'completed', 'failed', 'cancelled'
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (batch_id) REFERENCES transfer_batches(id) ON DELETE CASCADE,
    FOREIGN KEY (operation_type_id) REFERENCES operation_types(id) ON DELETE RESTRICT,
    FOREIGN KEY (sender_client_id) REFERENCES clients(id) ON DELETE SET NULL,
    FOREIGN KEY (receiver_client_id) REFERENCES clients(id) ON DELETE SET NULL,
    FOREIGN KEY (destination_operator_id) REFERENCES operators(id) ON DELETE RESTRICT,
    CHECK (amount > 0),
    CHECK (fee_amount >= 0),
    CHECK (commission_amount >= 0)
);

-- Index pour optimiser les recherches
CREATE INDEX IF NOT EXISTS idx_phone_prefixes_prefix ON phone_prefixes(prefix);
CREATE INDEX IF NOT EXISTS idx_clients_phone_number ON clients(phone_number);
CREATE INDEX IF NOT EXISTS idx_transactions_reference ON transactions(transaction_reference);
CREATE INDEX IF NOT EXISTS idx_transactions_created_at ON transactions(created_at);
CREATE INDEX IF NOT EXISTS idx_transactions_sender ON transactions(sender_client_id);
CREATE INDEX IF NOT EXISTS idx_transactions_receiver ON transactions(receiver_client_id);
CREATE INDEX IF NOT EXISTS idx_fee_brackets_operation ON fee_brackets(operation_type_id);


CREATE TABLE IF NOT EXISTS promotions (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    source_operator_id INTEGER NOT NULL,
    destination_operator_id INTEGER NOT NULL,
    promotion_percentage DECIMAL(5, 2) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (source_operator_id) REFERENCES operators(id) ON DELETE CASCADE,
    FOREIGN KEY (destination_operator_id) REFERENCES operators(id) ON DELETE CASCADE
);


-- ==========================================
-- INSERTION DES DONNÉES INITIALES (V2)
-- ==========================================

-- Opérateur de démo (admin / admin123)
-- Le hash de "admin123" généré par password_hash('admin123', PASSWORD_BCRYPT)
INSERT INTO operators (id, username, password_hash, name, code, is_main_operator) VALUES 
(1, 'admin', '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', 'OPERATEUR_A', 'OP_A', 1);

-- Opérateurs externes
INSERT INTO operators (id, username, password_hash, name, code, is_main_operator) VALUES 
(2, 'op_b_dummy', 'dummy', 'OPERATEUR_B', 'OP_B', 0),
(3, 'op_c_dummy', 'dummy', 'OPERATEUR_C', 'OP_C', 0);

-- Préfixes téléphoniques par défaut avec affectation
INSERT INTO phone_prefixes (prefix, operator_id, is_active) VALUES 
('033', 1, 1),
('037', 1, 1),
('032', 2, 1),
('031', 2, 1),
('034', 3, 1),
('035', 3, 1);

-- Commissions inter-opérateurs (OP_A vers OP_B et OP_C)
INSERT INTO operator_commissions (source_operator_id, destination_operator_id, commission_percentage) VALUES 
(1, 2, 2.00),
(1, 3, 3.00);

-- Types d'opérations
INSERT INTO operation_types (id, code, name, is_active) VALUES 
(1, 'DEPOSIT', 'Dépôt', 1),
(2, 'WITHDRAWAL', 'Retrait', 1),
(3, 'TRANSFER', 'Transfert', 1);

-- Barèmes de frais pour le Dépôt (souvent 0 ou fixe dans la réalité, ici un exemple)
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount) VALUES 
(1, 0, 999999999, 0); -- Les dépôts sont gratuits

-- Barèmes de frais pour le Retrait (exemple)
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount) VALUES 
(2, 100, 1000, 50),
(2, 1001, 5000, 100),
(2, 5001, 10000, 200),
(2, 10001, 25000, 500),
(2, 25001, 50000, 1000),
(2, 50001, 100000, 2000),
(2, 100001, 250000, 4000),
(2, 250001, 500000, 8000),
(2, 500001, 1000000, 12000),
(2, 1000001, 999999999, 15000);

-- Barèmes de frais pour le Transfert (exemple de l'énoncé)
INSERT INTO fee_brackets (operation_type_id, min_amount, max_amount, fee_amount) VALUES 
(3, 100, 1000, 50),
(3, 1001, 5000, 50),
(3, 5001, 10000, 100),
(3, 10001, 25000, 200),
(3, 25001, 50000, 400),
(3, 50001, 100000, 800),
(3, 100001, 250000, 1500),
(3, 250001, 500000, 1500),
(3, 500001, 1000000, 2500),
(3, 1000001, 2000000, 3000),
(3, 2000001, 999999999, 5000);

-- Clients de démo
INSERT INTO clients (phone_number, balance) VALUES 
('0340000001', 50000.00),
('0320000002', 150000.00),
('0330000003', 0.00);
\ n - -   T a b l e   d e s   c o m m i s s i o n s   ( V 3 ) \ n C R E A T E   T A B L E   I F   N O T   E X I S T S   c o m m i s s i o n s   ( \ n         i d   I N T E G E R   P R I M A R Y   K E Y   A U T O I N C R E M E N T , \ n         o p e r a t o r _ i d   I N T E G E R   N O T   N U L L , \ n         c o m m i s s i o n _ p e r c e n t a g e   D E C I M A L ( 5 ,   2 )   N O T   N U L L   D E F A U L T   5 . 0 0 , \ n         i s _ a c t i v e   B O O L E A N   D E F A U L T   1 , \ n         c r e a t e d _ a t   D A T E T I M E   D E F A U L T   C U R R E N T _ T I M E S T A M P , \ n         u p d a t e d _ a t   D A T E T I M E   D E F A U L T   C U R R E N T _ T I M E S T A M P , \ n         F O R E I G N   K E Y   ( o p e r a t o r _ i d )   R E F E R E N C E S   o p e r a t o r s ( i d )   O N   D E L E T E   C A S C A D E \ n ) ; \ n 
 
 