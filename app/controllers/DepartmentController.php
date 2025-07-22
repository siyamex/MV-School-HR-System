<?php

class DepartmentController extends Controller {
    private $departmentModel;
    private $userModel;
    private $employeeModel;

    public function __construct() {
        parent::__construct();
        $this->checkAuth();
        $this->departmentModel = $this->loadModel('Department');
        $this->userModel = $this->loadModel('User');
        $this->employeeModel = $this->loadModel('Employee');
    }

    public function index() {
        $this->checkRole(['super_admin', 'admin']);

        try {
            error_log("Department index: Starting...");
            
            // Use the existing method from Department model that supports complex queries
            $departments = $this->departmentModel->getAllWithHead();
            error_log("Department index: Got " . count($departments) . " departments");
            
            // Add employee count for each department
            foreach ($departments as &$dept) {
                $dept['employee_count'] = $this->departmentModel->getEmployeeCount($dept['id']);
            }
            error_log("Department index: Added employee counts");

            $this->loadView('departments/index', [
                'pageTitle' => 'Department Management',
                'departments' => $departments
            ]);

        } catch (Exception $e) {
            error_log("Department index error: " . $e->getMessage());
            error_log("Department index stack: " . $e->getTraceAsString());
            Session::flash('error', 'Error loading departments.');
            $this->redirect('dashboard');
        }
    }

    public function create() {
        $this->checkRole(['super_admin', 'admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();

            try {
                $data = [
                    'name' => trim($_POST['name'] ?? ''),
                    'description' => trim($_POST['description'] ?? ''),
                    'head_id' => !empty($_POST['head_id']) ? (int)$_POST['head_id'] : null,
                    'is_active' => isset($_POST['is_active']) ? 1 : 0
                ];

                // Validate required fields
                if (empty($data['name'])) {
                    throw new Exception('Department name is required');
                }

                // Check if department name already exists
                $existing = $this->departmentModel->findOne(['name' => $data['name']]);
                if ($existing) {
                    throw new Exception('Department name already exists');
                }

                // Validate head_id if provided
                if ($data['head_id']) {
                    $head = $this->userModel->find($data['head_id']);
                    if (!$head || !in_array($head['role'], ['admin', 'supervisor'])) {
                        throw new Exception('Selected head must be an admin or supervisor');
                    }
                }

                $departmentId = $this->departmentModel->create($data);

                $this->logActivity("Created department: {$data['name']}");
                Session::flash('success', 'Department created successfully.');
                $this->redirect('departments');

            } catch (Exception $e) {
                Session::flash('error', 'Failed to create department: ' . $e->getMessage());
            }
        }

        // Get potential heads (admins and supervisors)
        $potentialHeads = $this->userModel->findAll([
            'where' => "role IN ('admin', 'supervisor') AND is_active = 1",
            'orderBy' => 'name ASC'
        ]);

        $this->loadView('departments/create', [
            'pageTitle' => 'Create Department',
            'potentialHeads' => $potentialHeads
        ]);
    }

    public function edit($id) {
        $this->checkRole(['super_admin', 'admin']);

        $department = $this->departmentModel->findWithHead($id);
        if (!$department) {
            Session::flash('error', 'Department not found.');
            $this->redirect('departments');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();

            try {
                $data = [
                    'name' => trim($_POST['name'] ?? ''),
                    'description' => trim($_POST['description'] ?? ''),
                    'head_id' => !empty($_POST['head_id']) ? (int)$_POST['head_id'] : null,
                    'is_active' => isset($_POST['is_active']) ? 1 : 0
                ];

                // Validate required fields
                if (empty($data['name'])) {
                    throw new Exception('Department name is required');
                }

                // Check if department name already exists (excluding current department)
                $existing = $this->departmentModel->findAll(['name' => $data['name']]);
                foreach ($existing as $dept) {
                    if ($dept['id'] != $id) {
                        throw new Exception('Department name already exists');
                    }
                }

                // Validate head_id if provided
                if ($data['head_id']) {
                    $head = $this->userModel->find($data['head_id']);
                    if (!$head || !in_array($head['role'], ['admin', 'supervisor'])) {
                        throw new Exception('Selected head must be an admin or supervisor');
                    }
                }

                $this->departmentModel->update($id, $data);

                Session::flash('success', 'Department updated successfully.');
                $this->redirect('departments');

            } catch (Exception $e) {
                Session::flash('error', 'Failed to update department: ' . $e->getMessage());
            }
        }

        // Get potential heads (admins and supervisors)
        $potentialHeads = $this->userModel->findAll(['role' => 'admin']);
        $supervisors = $this->userModel->findAll(['role' => 'supervisor']);
        $potentialHeads = array_merge($potentialHeads, $supervisors);

        $this->loadView('departments/edit', [
            'pageTitle' => 'Edit Department',
            'department' => $department,
            'potentialHeads' => $potentialHeads
        ]);
    }

    public function delete($id) {
        $this->checkRole(['super_admin', 'admin']);
        $this->validateCSRF();

        try {
            $department = $this->departmentModel->find($id);
            if (!$department) {
                throw new Exception('Department not found');
            }

            // Check if department has employees
            $employeeCount = $this->employeeModel->count(['department_id' => $id]);
            if ($employeeCount > 0) {
                throw new Exception('Cannot delete department with employees. Please reassign employees first.');
            }

            $this->departmentModel->delete($id);

            $this->logActivity("Deleted department: {$department['name']}");
            Session::flash('success', 'Department deleted successfully.');

        } catch (Exception $e) {
            Session::flash('error', 'Failed to delete department: ' . $e->getMessage());
        }

        $this->redirect('departments');
    }

    public function view($id) {
        $this->checkRole(['super_admin', 'admin', 'supervisor']);

        $department = $this->departmentModel->findWithHead($id);

        if (!$department) {
            Session::flash('error', 'Department not found.');
            $this->redirect('departments');
        }

        // Get department employees - simple query using findAll
        $employees = $this->employeeModel->findAll(['department_id' => $id]);
        
        // Get user details for each employee
        foreach ($employees as &$employee) {
            if ($employee['user_id']) {
                $user = $this->userModel->find($employee['user_id']);
                if ($user) {
                    $employee['name'] = $user['name'];
                    $employee['email'] = $user['email'];
                }
            }
        }

        // Get employee count
        $employeeCount = $this->departmentModel->getEmployeeCount($id);

        $this->loadView('departments/view', [
            'pageTitle' => $department['name'],
            'department' => $department,
            'employees' => $employees,
            'employeeCount' => $employeeCount
        ]);
    }

    public function assignHead($id) {
        $this->checkRole(['super_admin', 'admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();

            try {
                $department = $this->departmentModel->find($id);
                if (!$department) {
                    throw new Exception('Department not found');
                }

                $headId = !empty($_POST['head_id']) ? (int)$_POST['head_id'] : null;

                if ($headId) {
                    $head = $this->userModel->find($headId);
                    if (!$head || !in_array($head['role'], ['admin', 'supervisor'])) {
                        throw new Exception('Selected head must be an admin or supervisor');
                    }
                }

                $this->departmentModel->update($id, ['head_id' => $headId]);

                $headName = $headId ? $head['name'] : 'None';
                $this->logActivity("Assigned head '{$headName}' to department: {$department['name']}");
                
                Session::flash('success', 'Department head assigned successfully.');

            } catch (Exception $e) {
                Session::flash('error', 'Failed to assign head: ' . $e->getMessage());
            }
        }

        $this->redirect('departments');
    }

    private function getDepartmentStats($departmentId) {
        try {
            $stats = [
                'total_employees' => 0,
                'active_employees' => 0,
                'pending_leaves' => 0,
                'pending_overtime' => 0
            ];

            // Total employees
            $stats['total_employees'] = $this->employeeModel->count(['department_id' => $departmentId]);
            
            // Active employees
            $stats['active_employees'] = $this->employeeModel->count([
                'department_id' => $departmentId,
                'status' => 'active'
            ]);

            // Pending leaves
            $leaveModel = $this->loadModel('LeaveRequest');
            $stats['pending_leaves'] = $leaveModel->count([
                'join' => 'JOIN employees e ON leave_requests.employee_id = e.id',
                'where' => 'e.department_id = ? AND leave_requests.status = "pending"',
                'params' => [$departmentId]
            ]);

            // Pending overtime
            $overtimeModel = $this->loadModel('OvertimeRequest');
            $stats['pending_overtime'] = $overtimeModel->count([
                'join' => 'JOIN employees e ON overtime_requests.employee_id = e.id',
                'where' => 'e.department_id = ? AND overtime_requests.status = "pending"',
                'params' => [$departmentId]
            ]);

            return $stats;

        } catch (Exception $e) {
            error_log("Error getting department stats: " . $e->getMessage());
            return [
                'total_employees' => 0,
                'active_employees' => 0,
                'pending_leaves' => 0,
                'pending_overtime' => 0
            ];
        }
    }
    
    public function debug() {
        // Bypass authentication for debugging
        echo "<h1>Department Debug</h1>";
        
        try {
            echo "<h2>Testing database connection...</h2>";
            $db = Database::getInstance();
            echo "✓ Database connected<br>";
            
            echo "<h2>Testing Department model...</h2>";
            $dept = new Department();
            echo "✓ Department model loaded<br>";
            
            echo "<h2>Testing simple query...</h2>";
            $count = $db->fetch("SELECT COUNT(*) as count FROM departments");
            echo "✓ Departments count: " . $count['count'] . "<br>";
            
            echo "<h2>Testing getAllWithHead...</h2>";
            $departments = $dept->getAllWithHead();
            echo "✓ getAllWithHead returned " . count($departments) . " departments<br>";
            
            echo "<h2>Department data:</h2>";
            echo "<pre>" . print_r($departments, true) . "</pre>";
            
            echo "<h2>Testing getEmployeeCount...</h2>";
            if (!empty($departments)) {
                $empCount = $dept->getEmployeeCount($departments[0]['id']);
                echo "✓ Employee count for first department: " . $empCount . "<br>";
            }
            
        } catch (Exception $e) {
            echo "✗ Error: " . $e->getMessage() . "<br>";
            echo "Stack trace:<br><pre>" . $e->getTraceAsString() . "</pre>";
        }
        
        exit;
    }
}
