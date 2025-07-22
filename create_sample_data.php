<?php
require_once 'config/bootstrap.php';

// Create Database connection
$database = Database::getInstance();

// Check if we already have employees
$existingEmployees = $database->fetchAll("SELECT COUNT(*) as count FROM employees");
if ($existingEmployees[0]['count'] > 0) {
    echo "Employees already exist. Skipping sample data creation.\n";
    exit;
}

echo "Creating sample users and employees...\n";

// Sample users (employees)
$sampleUsers = [
    [
        'name' => 'John Smith',
        'email' => 'john.smith@hrschool.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'role' => 'staff'
    ],
    [
        'name' => 'Jane Doe',
        'email' => 'jane.doe@hrschool.com', 
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'role' => 'admin'
    ],
    [
        'name' => 'Michael Johnson',
        'email' => 'michael.johnson@hrschool.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'role' => 'staff'
    ],
    [
        'name' => 'Sarah Wilson',
        'email' => 'sarah.wilson@hrschool.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
        'role' => 'supervisor'
    ]
];

// Insert users and create corresponding employees
foreach ($sampleUsers as $index => $user) {
    // Insert user
    $sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)";
    $params = [$user['name'], $user['email'], $user['password'], $user['role']];
    $database->query($sql, $params);
    
    $userId = $database->getConnection()->lastInsertId();
    
    // Create employee record
    $employeeId = 'EMP' . str_pad($index + 1, 4, '0', STR_PAD_LEFT);
    $departmentId = ($index % 5) + 1; // Rotate through departments 1-5
    
    $designations = ['Teacher', 'Administrator', 'IT Specialist', 'HR Manager'];
    $designation = $designations[$index % count($designations)];
    
    $sql = "INSERT INTO employees (user_id, employee_id, department_id, designation, phone, address, hire_date, salary, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $params = [
        $userId,
        $employeeId,
        $departmentId,
        $designation,
        '+1234567' . str_pad($index, 3, '0', STR_PAD_LEFT),
        '123 Main St, City, State',
        date('Y-m-d', strtotime('-' . ($index * 30 + 30) . ' days')),
        50000 + ($index * 10000),
        'active'
    ];
    $database->query($sql, $params);
    
    echo "Created user: {$user['name']} with employee ID: $employeeId\n";
}

echo "Sample data created successfully!\n";
echo "You can now test the employee page.\n";
