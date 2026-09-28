<?php

class LeaveService
{
    public function __construct(
        private PDO $pdo,
        private MailService $mailService
    ) {
    }

    public function approveLeaves(int $managerId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT *
            FROM leaves
            WHERE status = :status
        ");

        $stmt->execute([
            'status' => 'pending'
        ]);

        $leaves = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $approvedLeaves = [];

        foreach ($leaves as $leave) {
            $employeeStmt = $this->pdo->prepare("
                SELECT
                    e.id,
                    e.email,
                    e.department_id,
                    d.id AS department_id,
                    d.manager_id
                FROM employees e
                INNER JOIN departments d
                    ON d.id = e.department_id
                WHERE e.id = :employee_id
            ");

            $employeeStmt->execute([
                'employee_id' => $leave['employee_id']
            ]);

            $employee = $employeeStmt->fetch(PDO::FETCH_ASSOC);

            if (!$employee) {
                continue;
            }

            if ((int) $employee['manager_id'] !== $managerId) {
                continue;
            }

            $updateStmt = $this->pdo->prepare("
                UPDATE leaves
                SET
                    status = :status,
                    approved_by = :approved_by
                WHERE id = :id
                  AND status = 'pending'
            ");

            $updateStmt->execute([
                'status'      => 'approved',
                'approved_by' => $managerId,
                'id'          => $leave['id']
            ]);

            $leave['status'] = 'approved';
            $leave['approved_by'] = $managerId;

            $this->mailService->sendLeaveApproved(
                $employee['email'],
                $leave
            );

            $approvedLeaves[] = $leave;
        }

        return $approvedLeaves;
    }
}
