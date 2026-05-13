<?php
namespace App\Controllers;

use App\Models\EmployeModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to('/' . session()->get('role') . '/dashboard');
        }
        return view('auth/login');
    }

    public function loginPost()
    {
        
        if (!$this->validate([
            'email'    => 'required|valid_email',
            'password' => 'required|min_length[6]',
        ])) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        
        $employeModel = new EmployeModel();
        $employe = $employeModel->where('email', $email)->first();

       
        if ($employe && password_verify($password, $employe['password'])) {
            session()->set([
                'user_id'   => $employe['id'],
                'email'     => $employe['email'],
                'nom'       => $employe['nom'],
                'prenom'    => $employe['prenom'],
                'role'      => $employe['role'],  // ← Important
                'isLoggedIn' => true,
            ]);
            return redirect()->to('/' . $employe['role'] . '/dashboard')
                            ->with('success', 'Connexion réussie!');
        }

        
        return redirect()->back()->withInput()->with('error', 'Identifiants incorrects');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'Déconnecté');
    }
}