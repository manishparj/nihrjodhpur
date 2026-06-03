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

-- Create phd_programmes table
CREATE TABLE `phd_programmes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `programme_name` varchar(255) NOT NULL,
  `session` varchar(100) NOT NULL,
  `status` enum('open','closed') DEFAULT 'open',
  `last_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create phd_documents table for multiple documents
CREATE TABLE `phd_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `phd_programme_id` int(11) NOT NULL,
  `doc_title` varchar(255) NOT NULL,
  `doc_file` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `phd_programme_id` (`phd_programme_id`),
  CONSTRAINT `phd_documents_ibfk_1` FOREIGN KEY (`phd_programme_id`) REFERENCES `phd_programmes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE IF NOT EXISTS carousel_galleries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    subtitle TEXT,
    cover_photo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)

CREATE TABLE IF NOT EXISTS carousel_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    gallery_id INT NOT NULL,
    image_url VARCHAR(255) NOT NULL,
    caption TEXT,
    display_order INT DEFAULT 0,
    FOREIGN KEY (gallery_id) REFERENCES carousel_galleries(id) ON DELETE CASCADE
)


-- Main scientist table (for authentication)
CREATE TABLE `employees_scientists` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `name_hi` varchar(255) DEFAULT NULL,
  `gender` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `area_of_work` text,
  `profile_completed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);

-- Academic Qualification
CREATE TABLE `scientist_academic_qualification` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `qualification` text,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Research Interest
CREATE TABLE `scientist_research_interest` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `interest` text,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Membership/Affiliation
CREATE TABLE `scientist_membership` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `membership` text,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Fellowship/Awards
CREATE TABLE `scientist_fellowship` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `fellowship` text,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Publications
CREATE TABLE `scientist_publications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `title` text,
  `year` varchar(10) DEFAULT NULL,
  `link` text,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Academic Identity
CREATE TABLE `scientist_academic_identity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `orcid_id` text,
  `scopus_id` text,
  `google_scholar` text,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Experience
CREATE TABLE `scientist_experience` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `institute` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `currently_working` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Citations/H-Index
CREATE TABLE `scientist_citations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `citations` varchar(50) DEFAULT NULL,
  `hindex` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Social Media
CREATE TABLE `scientist_social` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `platform` varchar(100) DEFAULT NULL,
  `link` text,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Books
CREATE TABLE `scientist_books` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `title` text,
  `year` varchar(10) DEFAULT NULL,
  `link` text,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Patents
CREATE TABLE `scientist_patents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `title` text,
  `year` varchar(10) DEFAULT NULL,
  `link` text,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Awards and Honors
CREATE TABLE `scientist_awards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `title` text,
  `year` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Training
CREATE TABLE `scientist_training` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `title` text,
  `year` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Completed Projects
CREATE TABLE `scientist_completed_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `title` text,
  `short_name` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- Ongoing Projects
CREATE TABLE `scientist_ongoing_projects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `title` text,
  `short_name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

-- CV Uploads
CREATE TABLE `scientist_cv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `scientist_id` int(11) NOT NULL,
  `cv_path` text,
  `uploaded_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `scientist_id` (`scientist_id`),
  FOREIGN KEY (`scientist_id`) REFERENCES `employees_scientists`(`id`) ON DELETE CASCADE
);

ALTER TABLE `employees_scientists` ADD `profile_photo` VARCHAR(500) NULL AFTER `profile_completed`;


CREATE TABLE `nodal_officers` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `role` varchar(100) NOT NULL,
    `name_designation` varchar(255) NOT NULL,
    `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;



-- Insert Nodal Officers Data
INSERT INTO `nodal_officers` (`role`, `name_designation`) VALUES
('Nodal Officer for Disciplinary Proceedings', 'Prof. (Dr.) Pankaj Bhardwaj, Director'),
('Nodal Officer for ICMR Pensioners'' Portal', 'Prof. (Dr.) Pankaj Bhardwaj, Director'),
('Nodal Officer for Shram Suvidha', 'Dr. Ramesh Kumar Huda, Scientist-D'),
('Nodal Officer for Capacity Building Unit (CBU) – Mission Karmayogi', 'Dr. Ramesh Kumar Sangwan, Scientist-C'),
('Nodal Officer for Darpan (Primary)', 'Dr. Ramesh Kumar Sangwan, Scientist-C'),
('Nodal Officer for Darpan (Alternate)', 'Dr. Janesh Kumar Gautam, Scientist-C'),
('Nodal Officer for i-RISE (ICMR-Research Infrastructure Sharing Ecosystem)', 'Dr. Harvinder Singh, Scientist-B'),
('Liaison Officer for SC/ST', 'Dr. Janesh Kumar Gautam, Scientist-C'),
('Liaison Officer for OBC/EWS/PWD', 'Dr. Rina Kumawat, Scientist-C'),
('Public Grievance Officer', 'Dr. Rina Kumawat, Scientist-C'),
('Compliance Officer', 'Mr. Anurag Bithu, Scientist-B'),
('Administrative Information', 'Mr. Sunil Bishnoi, Section Officer'),
('Nodal Officer for Sparrow', 'Mr. Sunil Bishnoi, Section Officer'),
('Nodal Officer for IRRAS', 'Mr. Sunil Bishnoi, Section Officer'),
('Nodal Officer for eHRMS', 'Mr. Sunil Bishnoi, Section Officer'),
('Nodal Officer for Pensioners', 'Dr. P.K. Anand, Sci-F'),
('Nodal Officer for Court Cases', 'Dr. Ramesh Kumar Sangwan, Scientist-C'),
('Nodal Officer for RTI', 'Sh. Pankaj Kumar, Technical Officer'),
('CPIO (Admin & Accounts)', 'Sh. Sunil Bishnoi, SO'),
('CPIO (Scientific/Technical)', 'Dr. Ramesh Kumar Sangwan, Scientist-C'),
('1st Appellate Authority (Admin & Accounts)', 'Sh. Dinesh Soni, Sr. AO'),
('1st Appellate Authority (Scientific/Technical)', 'Dr. P.K. Anand, Sci-F'),
('Nodal Officer for Security and Fire', 'Dr. Anil Purohit, Sr. Tech. Officer-III'),
('Nodal Officer for Maintenance of Institute Campus and Residential Quarters', 'Dr. Anil Purohit, Sr. Tech. Officer-III'),
('Nodal Officer for Procurement & GeM', 'Sh. Ranglal Meena, TO-C'),
('Nodal Officer for e-Office & e-Governance/Web VPN', 'Dr. Ramesh Kumar Sangwan, Scientist-C'),
('Nodal Officer for Suo Motu Writ Petition (C)', 'Sh. Manish Prajapati, Technical Assistant'),
('Research Integrity Officer (RIOs)', 'Dr. Ramesh Kumar Huda, Sci-D'),
('Biosafety and Biosecurity Cell (B2Cell)', 'Dr. Harvinder Singh, Sci-B'),
('Nodal Officer for Bio Medical Waste', 'Dr. Harvinder Singh, Sci-B'),
('Nodal Officer for Disaster Management', 'Dr. Harvinder Singh, Sci-B'),
('Primary User for GeM', 'Dr. Suresh Yadav, Sci-C'),
('Nodal Officer for NIC & NKN', 'Dr. P.K. Anand, Sci-F'),
('Nodal Officer for Aadhar Based Attendance (AEBAS)', 'Dr. Ramesh Kumar Sangwan, Scientist-C'),
('Welfare Officer', 'Sh. Manish Prajapati, TA'),
('AcSI R Coordinator', 'Sh. Manohar Singh Seervi, Assistant'),
('Academic Officer', 'Dr. Ramesh Kumar Huda, Sci-D'),
('Library In-Charge', 'Dr. Janesh Kumar Gautam, Scientist-C'),
('Security In-Charge', 'Sh. K. L. Sharma, MSW'),
('Guest House In-Charge', 'Dr. Suresh Yadav, Scientist-C'),
('Transport In-Charge with Signatory for Diesel/Petrol Requisition Slip', 'Dr. Janesh Kumar Gautam, Scientist-C'),
('Hindi Rajbhasha Officer', 'Sh. Sunil Bishnoi, SO'),
('Information Security Officer (ISO)', 'Sh. Manish Prajapati, TA'),
('Nodal Officer for MRHRU (Nodal Team)', 'Prof. (Dr.) Pankaj Bhardwaj, Director – Overall Supervision'),
('Nodal Officer for Mission Mode Recruitment/Rozgar Mela', 'Dr. P.K. Anand, Sci-F'),
('Vigilance Officer', 'Dr. Suresh Yadav, Sci-C'),
('Cultural & Sports Activities In-Charge', 'Dr. R.K. Sangwan, Sci-C'),
('Nodal Officer IEC (Institute Ethics Committee)', 'Sh. Sunil Bishnoi, Section Officer'),
('Women Welfare Officer', 'Dr. Mukti Khetan, Sci-C'),
('Nodal Officer for IPD Medical Treatment', 'Dr. Ramesh Kumar Huda, Sci-D'),
('Nodal Officer(s) of Media Relation / PRO', 'Dr. P.K. Anand, Sci-F; Dr. Rina Kumawat, Sci-C'),
('Nodal Officer for Environmental Safety / Green Campus', 'Dr. P.K. Anand, Sci-F; Dr. Anurag Bithu, Sci-B; Dr. Kanchan Bala, JTO; Dr. Suresh Yadav, Sci-C');