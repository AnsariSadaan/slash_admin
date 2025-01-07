<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuditLogModel;

class AuditLogController extends BaseController
{

    public function Auditlog()
    {
        if (!$this->session->has('user')) {
            return redirect()->to('/login');
        }
        $auditlog_model = new AuditLogModel();
        $loggedInUser = $this->session->get('user');
        $role = $loggedInUser->roles;
        $auditlog = $auditlog_model->getAllAuditLogs();
        $mainContent = view('auditlog', ['auditlog' => $auditlog, 'role' => $role, 'loggedInUser' => $loggedInUser]);
        return view('Template', ['mainContent' => $mainContent]);
    }
}
