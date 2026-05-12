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

-- Table for RTI records (CPIO and FAA data)
CREATE TABLE IF NOT EXISTS rti_officers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject_matter VARCHAR(255) NOT NULL,
    
    -- CPIO Details
    cpio_name VARCHAR(255) NOT NULL,
    cpio_designation VARCHAR(255) NOT NULL,
    cpio_email VARCHAR(255) NOT NULL,
    cpio_phone VARCHAR(50) NOT NULL,
    
    -- FAA Details
    faa_name VARCHAR(255) NOT NULL,
    faa_designation VARCHAR(255) NOT NULL,
    faa_email VARCHAR(255) NOT NULL,
    faa_phone VARCHAR(50) NOT NULL,
    
    display_order INT DEFAULT 0,
    status TINYINT DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for Nodal Officer
CREATE TABLE IF NOT EXISTS rti_nodal_officer (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    designation VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table for RTI Documents
CREATE TABLE IF NOT EXISTS rti_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    document_type ENUM('english_act', 'hindi_act', 'office_order') NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_size INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS modals (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    modal_title VARCHAR(255) NOT NULL,
    modal_content TEXT NOT NULL,
    footer_text VARCHAR(100) DEFAULT 'Close',
    is_enabled TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
-- Create internship_programmes table
CREATE TABLE `internship_programmes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `last_date` date NOT NULL,
  `duration` enum('six_months','two_months','three_months','one_year') DEFAULT 'six_months',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create internship_documents table for multiple documents
CREATE TABLE `internship_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `internship_id` int(11) NOT NULL,
  `doc_title` varchar(255) NOT NULL,
  `doc_file` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `internship_id` (`internship_id`),
  CONSTRAINT `internship_documents_ibfk_1` FOREIGN KEY (`internship_id`) REFERENCES `internship_programmes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;