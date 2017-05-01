<?php
namespace Tests\Units\App\Libraries\Calendrier\Collection;

use App\Libraries\Calendrier\Collection\Weekend as _Weekend;

class Weekend extends \Tests\Units\TestUnit
{
    public function beforeTestMethod($method)
    {
        $this->result = new \mock\MYSQLIResult();
        $this->db = new \mock\includes\SQL();
        $this->calling($this->db)->query = $this->result;
    }

    private $db;
    private $result;

    public function testGetListeWeekendTravaille()
    {
        $this->calling($this->result)->fetch_assoc = ['conf_valeur' => false];
        $date = new \DateTimeImmutable();

        $weekend = new _Weekend($this->db, $date, $date);

        $this->array($weekend->getListe())->isEmpty();
    }

    public function testGetListeSamediNonTravailleSeulement()
    {
        $this->calling($this->result)->fetch_assoc[1] = ['conf_valeur' => false];
        $this->calling($this->result)->fetch_assoc[2] = ['conf_valeur' => 'TRUE'];
        $debut = new \DateTimeImmutable('2017-02-01');
        $fin = new \DateTimeImmutable('2017-02-28');

        $weekend = new _Weekend($this->db, $debut, $fin);

        $expected = [
            '2017-02-04',
            '2017-02-11',
            '2017-02-18',
            '2017-02-25'
        ];

        $this->array($weekend->getListe())->isIdenticalTo($expected);
    }

    public function testGetListeDimancheNonTravailleSeulement()
    {
        $this->calling($this->result)->fetch_assoc[1] = ['conf_valeur' => 'TRUE'];
        $this->calling($this->result)->fetch_assoc[2] = ['conf_valeur' => false];
        $debut = new \DateTimeImmutable('2017-02-01');
        $fin = new \DateTimeImmutable('2017-02-28');

        $weekend = new _Weekend($this->db, $debut, $fin);

        $expected = [
            '2017-02-05',
            '2017-02-12',
            '2017-02-19',
            '2017-02-26'
        ];

        $this->array($weekend->getListe())->isIdenticalTo($expected);
    }
}
