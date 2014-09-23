<?php
namespace Entity; use Doctrine\ORM\Mapping as ORM;

interface PersistableBase {
    public function delete();
    public function isDeleted();
    public function getCreated();
    public function getUpdated();
}

?>
