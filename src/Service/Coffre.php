<?php


namespace App\Service;

class Coffre
{
    private $item = [];
    private $status = 'closed';
    private $mdp;
    private $mdpStatus = false;

    public function openCoffre()
    {
        $this->status = 'opened';
    }
    public function closeCoffre()
    {
        $this->status = 'closed';
    }
    public function getStatus()
    {
        return $this->status;
    }
    public function setStatus($status)
    {
        $this->status = $status;
    }
    public function registerItem($item)
    {
        $this->item[] = $item;
    }
    public function getAllItem()
    {
        return $this->item;
    }
    public function setMdp($mdp)
    {
        $this->mdp = $mdp;
    }
    public function getMdp()
    {
        return $this->mdp;
    }
    public function checkMdp($mdp)
    {
        if($mdp == $this->mdp)
        {
            return true;
        }
    }
    public function getMdpStatus()
    {
        return $this->mdpStatus;
    }
    public function setMdpStatus($mdpStatus)
    {
        $this->mdpStatus = $mdpStatus;
    }

}
