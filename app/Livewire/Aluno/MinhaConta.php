<?php

namespace App\Livewire\Aluno;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class MinhaConta extends Component
{
    public $name;
    public $email;
    public $cpf;
    public $telefone;
    public $auto_escola;
    public $current_password;
    public $password;
    public $password_confirmation;

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->cpf = $user->cpf;
        $this->telefone = $user->telefone;
        $this->auto_escola = $user->auto_escola;
    }

    public function updateProfile()
    {
        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . Auth::id()],
            'cpf' => ['nullable', 'string', 'max:14'],
            'telefone' => ['nullable', 'string', 'max:30'],
            'auto_escola' => ['nullable', 'string', 'max:255'],
        ]);

        Auth::user()->update([
            'name' => $this->name,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'telefone' => $this->telefone,
            'auto_escola' => $this->auto_escola,
        ]);

        session()->flash('profile_updated', 'Perfil atualizado com sucesso!');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($this->current_password, Auth::user()->password)) {
            $this->addError('current_password', 'A senha atual está incorreta.');
            return;
        }

        Auth::user()->update([
            'password' => Hash::make($this->password),
        ]);

        $this->reset(['current_password', 'password', 'password_confirmation']);
        session()->flash('password_updated', 'Senha alterada com sucesso!');
    }

    public function render()
    {
        return view('livewire.aluno.minha-conta');
    }
}
