-- Create directors table (simplified)
CREATE TABLE directors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    photo VARCHAR(500) DEFAULT NULL,
    service_from DATE NOT NULL,
    service_to DATE DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_service_period (service_from, service_to)
);

-- Create committee table
CREATE TABLE committees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    committee_name VARCHAR(255) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create committee_members table (employee_name_designation is a single combined field)
CREATE TABLE committee_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    committee_id INT NOT NULL,
    employee_name_designation VARCHAR(255) NOT NULL COMMENT 'Combined field: Employee Name & Designation',
    role VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (committee_id) REFERENCES committees(id) ON DELETE CASCADE
);

-- Sample indexes for better performance
CREATE INDEX idx_committee_members_committee ON committee_members(committee_id);
CREATE INDEX idx_committee_name ON committees(committee_name);

-- Insert committees
INSERT INTO committees (committee_name) VALUES 
('Capital Works Advisory Committee (CWAC)'),
('Capital Works Monitoring Committee (CWMC)'),
('Academic Committee'),
('IT & Website Committee'),
('Sports Committee'),
('Transport Committee'),
('Security & Fire Safety Committee'),
('Guest House Management Committee (GHMC)'),
('Central Room Allotment Committee'),
('Internal Complaints Committee (ICC)'),
('MoU/MoA committee of ICMR-NIIRNCD, Jodhpur and MRHRU Jaipur'),
('Medical committee');


CREATE TABLE IF NOT EXISTS employees_directory (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    designation VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(20) NOT NULL,
    section VARCHAR(100) NOT NULL,
    serial_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)