<?php
namespace Entity; use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @author rruiz
 */
interface Auditable {
    public function auditable();
}

