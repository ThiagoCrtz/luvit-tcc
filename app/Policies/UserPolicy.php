<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determina se o usuário pode visualizar qualquer perfil
     * (Retorna true = perfis são públicos)
     */
    public function viewAny(?User $user): bool
    {
        return true; // Qualquer um pode ver a lista de usuários
    }

    /**
     * Determina se o usuário pode visualizar um perfil específico
     * (Retorna true = perfis são públicos)
     */
    public function view(?User $user, User $model): bool
    {
        return true; // Perfis são públicos - qualquer um pode ver
    }

    /**
     * Determina se o usuário pode criar novos usuários
     * (Registro é público, então true)
     */
    public function create(?User $user): bool
    {
        return true; // Qualquer um pode se registrar
    }

    /**
     * Determina se o usuário pode atualizar o perfil
     * IMPORTANTE: Apenas o dono do perfil pode editar
     */
    public function update(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    /**
     * Determina se o usuário pode deletar um perfil
     * IMPORTANTE: Apenas o dono pode deletar sua própria conta
     */
    public function delete(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    /**
     * Determina se o usuário pode restaurar um perfil deletado
     */
    public function restore(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }

    /**
     * Determina se o usuário pode deletar permanentemente
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->id === $model->id;
    }
}