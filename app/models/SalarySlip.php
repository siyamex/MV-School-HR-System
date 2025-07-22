<?php
/**
 * SalarySlip Model
 */
class SalarySlip extends Model {
    protected $table = 'salary_slips';
    
    public function getAllWithDetails($year = null, $month = null) {
        $sql = "SELECT ss.*, 
                       u.name as employee_name, 
                       e.employee_id,
                       d.name as department_name
                FROM {$this->table} ss
                JOIN employees e ON ss.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE 1=1";
        
        $params = [];
        
        if ($year) {
            $sql .= " AND ss.year = ?";
            $params[] = $year;
        }
        
        if ($month) {
            $sql .= " AND ss.month = ?";
            $params[] = $month;
        }
        
        $sql .= " ORDER BY ss.year DESC, ss.month DESC, u.name";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function findByEmployeeAndMonth($employeeId, $month, $year) {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE employee_id = ? AND month = ? AND year = ?",
            [$employeeId, $month, $year]
        );
    }
    
    public function getByEmployee($employeeId, $limit = null) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE employee_id = ? 
                ORDER BY year DESC, month DESC";
        
        if ($limit) {
            $sql .= " LIMIT " . $limit;
        }
        
        return $this->db->fetchAll($sql, [$employeeId]);
    }
    
    public function bulkUpload($salaryData) {
        $this->db->beginTransaction();
        
        try {
            $uploaded = 0;
            $errors = [];
            
            foreach ($salaryData as $row => $data) {
                try {
                    // Validate required fields
                    if (empty($data['employee_id']) || empty($data['month']) || empty($data['year'])) {
                        $errors[] = "Row {$row}: Missing required fields (employee_id, month, year)";
                        continue;
                    }
                    
                    // Find employee
                    $employeeModel = new Employee();
                    $employee = $employeeModel->findByEmployeeId($data['employee_id']);
                    
                    if (!$employee) {
                        $errors[] = "Row {$row}: Employee ID {$data['employee_id']} not found";
                        continue;
                    }
                    
                    // Check if salary slip already exists
                    $existing = $this->findByEmployeeAndMonth(
                        $employee['id'], 
                        $data['month'], 
                        $data['year']
                    );
                    
                    $salarySlipData = [
                        'employee_id' => $employee['id'],
                        'month' => $data['month'],
                        'year' => $data['year'],
                        'basic_salary' => $data['basic_salary'] ?? 0,
                        'allowances' => $data['allowances'] ?? 0,
                        'overtime_pay' => $data['overtime_pay'] ?? 0,
                        'gross_salary' => $data['gross_salary'] ?? 0,
                        'deductions' => $data['deductions'] ?? 0,
                        'tax' => $data['tax'] ?? 0,
                        'net_salary' => $data['net_salary'] ?? 0
                    ];
                    
                    // Calculate gross and net salary if not provided
                    if (empty($salarySlipData['gross_salary'])) {
                        $salarySlipData['gross_salary'] = $salarySlipData['basic_salary'] + 
                                                        $salarySlipData['allowances'] + 
                                                        $salarySlipData['overtime_pay'];
                    }
                    
                    if (empty($salarySlipData['net_salary'])) {
                        $salarySlipData['net_salary'] = $salarySlipData['gross_salary'] - 
                                                      $salarySlipData['deductions'] - 
                                                      $salarySlipData['tax'];
                    }
                    
                    if ($existing) {
                        $this->update($existing['id'], $salarySlipData);
                    } else {
                        $this->create($salarySlipData);
                    }
                    
                    $uploaded++;
                    
                } catch (Exception $e) {
                    $errors[] = "Row {$row}: " . $e->getMessage();
                }
            }
            
            $this->db->commit();
            
            return [
                'success' => true,
                'uploaded' => $uploaded,
                'errors' => $errors
            ];
            
        } catch (Exception $e) {
            $this->db->rollback();
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'uploaded' => 0,
                'errors' => []
            ];
        }
    }
    
    public function generatePDF($id) {
        $salarySlip = $this->findWithDetails($id);
        
        if (!$salarySlip) {
            throw new Exception('Salary slip not found');
        }
        
        // Generate PDF using a simple template
        // This is a basic implementation - you might want to use a proper PDF library
        $html = $this->generateHTMLTemplate($salarySlip);
        
        // For now, return HTML. In production, use libraries like TCPDF or mPDF
        return $html;
    }
    
    public function findWithDetails($id) {
        $sql = "SELECT ss.*, 
                       u.name as employee_name, 
                       u.email as employee_email,
                       e.employee_id,
                       e.designation,
                       e.hire_date,
                       d.name as department_name
                FROM {$this->table} ss
                JOIN employees e ON ss.employee_id = e.id
                JOIN users u ON e.user_id = u.id
                LEFT JOIN departments d ON e.department_id = d.id
                WHERE ss.id = ?";
        
        return $this->db->fetch($sql, [$id]);
    }
    
    private function generateHTMLTemplate($salarySlip) {
        $monthName = date('F', mktime(0, 0, 0, $salarySlip['month'], 10));
        
        return "
        <html>
        <head>
            <title>Salary Slip - {$salarySlip['employee_name']}</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; }
                .employee-info { margin-bottom: 20px; }
                .salary-details { width: 100%; border-collapse: collapse; }
                .salary-details th, .salary-details td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                .salary-details th { background-color: #f2f2f2; }
                .total-row { font-weight: bold; background-color: #f9f9f9; }
            </style>
        </head>
        <body>
            <div class='header'>
                <h2>SALARY SLIP</h2>
                <p>For the month of {$monthName} {$salarySlip['year']}</p>
            </div>
            
            <div class='employee-info'>
                <p><strong>Employee Name:</strong> {$salarySlip['employee_name']}</p>
                <p><strong>Employee ID:</strong> {$salarySlip['employee_id']}</p>
                <p><strong>Department:</strong> {$salarySlip['department_name']}</p>
                <p><strong>Designation:</strong> {$salarySlip['designation']}</p>
            </div>
            
            <table class='salary-details'>
                <tr><th>Particulars</th><th>Amount (IDR)</th></tr>
                <tr><td>Basic Salary</td><td>" . number_format($salarySlip['basic_salary'], 2) . "</td></tr>
                <tr><td>Allowances</td><td>" . number_format($salarySlip['allowances'], 2) . "</td></tr>
                <tr><td>Overtime Pay</td><td>" . number_format($salarySlip['overtime_pay'], 2) . "</td></tr>
                <tr class='total-row'><td>Gross Salary</td><td>" . number_format($salarySlip['gross_salary'], 2) . "</td></tr>
                <tr><td>Deductions</td><td>" . number_format($salarySlip['deductions'], 2) . "</td></tr>
                <tr><td>Tax</td><td>" . number_format($salarySlip['tax'], 2) . "</td></tr>
                <tr class='total-row'><td>Net Salary</td><td>" . number_format($salarySlip['net_salary'], 2) . "</td></tr>
            </table>
            
            <div style='margin-top: 40px;'>
                <p><strong>Generated on:</strong> " . date('d F Y') . "</p>
            </div>
        </body>
        </html>";
    }
    
    public function getMonthlyStats($year = null) {
        $year = $year ?: date('Y');
        
        $sql = "SELECT 
                    month,
                    COUNT(*) as total_slips,
                    SUM(gross_salary) as total_gross,
                    SUM(net_salary) as total_net,
                    AVG(net_salary) as avg_salary
                FROM {$this->table}
                WHERE year = ?
                GROUP BY month
                ORDER BY month";
        
        return $this->db->fetchAll($sql, [$year]);
    }
}
