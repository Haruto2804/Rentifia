<?php
namespace App\Model;
use App\Core\Database;
use DateTime;
use PDO;
class User
{
    private ?int $id;
    private string $username;
    private string $password_hash;
    private string $email;
    private string $role;
    private ?string $reset_token;
    private bool $is_active;
    private string $created_at;
    private string $updated_at;

    public function __construct(?int $id = null, string $username = '', string $password_hash = '', string $email = '', string $role = 'tenant', ?string $reset_token = null, bool $is_active = true, ?string $created_at = null, ?string $updated_at = null)
    {
        // Set provided properties
        $this->id = $id;
        $this->username = $username;
        $this->password_hash = $password_hash;
        $this->email = $email;
        $this->role = $role;
        $this->reset_token = $reset_token;
        $this->is_active = $is_active;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
    }
    // ================= GETTERS & SETTERS =================

    public function getId(): ?int
    {
        return $this->id ?? null;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username ?? null;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getPasswordHash(): ?string
    {
        return $this->password_hash ?? null;
    }

    public function setPasswordHash(string $password_hash): self
    {
        $this->password_hash = $password_hash;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email ?? null;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getRole(): string
    {
        return $this->role ?? 'tenant';
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token ?? null;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;
        return $this;
    }

    public function getIsActive(): bool
    {
        return $this->is_active ?? true;
    }

    public function setIsActive(bool $is_active): self
    {
        $this->is_active = $is_active;
        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at ?? null;
    }

    public function setCreatedAt(?string $created_at): self
    {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at ?? null;
    }

    public function setUpdatedAt(?string $updated_at): self
    {
        $this->updated_at = $updated_at;
        return $this;
    }


}
?>