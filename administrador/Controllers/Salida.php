<?php
class Salida
{
    public function __construct(string $redirectPath = '/login')
    {
        $this->logout($redirectPath);
    }

    /**
     * Destruye la sesión y redirige al login indicado.
     *
     * @param string $redirectPath Ruta relativa desde base_url (ej: '/loginAdmin')
     */
    public function logout(string $redirectPath = '/login')
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header('Location: ' . base_url() . $redirectPath);
        exit();
    }
}
?>
