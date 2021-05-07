<?php

interface InterfaceCargo {
  public function setNome(string $nome);

  public function getNome();

  public function setCarreira(string $carreira);

  public function getSalario();
}

class Desenvolvedor implements InterfaceCargo {

  private $nome;
  private $carreira;
  private $salario;

  public function __construct(string $carreira) {
    $this->setNome('Desenvolvedor');
    $this->setCarreira($carreira);
    $this->getSalario();
  }

  public function setNome(string $nome) {
    $this->nome = $nome;
  }

  public function getNome() {
    return $this->nome;
  }

  public function setCarreira(string $carreira) {
    $this->carreira = $carreira;
  }

  public function getSalario() {
    switch ($this->carreira) {
      case 'fullstack1':
        $this->salario = 2000.00;
        break;
      case 'fullstack2':
        $this->salario = 3000.00;
        break;
      case 'fullstack3':
        $this->salario = 4000.00;
        break;
      default:
        throw new Exception('Carreira inválida: '.$this->carreira);
    }

    return $this->salario;
  }
}

class Gestao implements InterfaceCargo {

  private $nome;
  private $carreira;
  private $salario;

  public function __construct(string $carreira) {
    $this->setNome('Gestão');
    $this->setCarreira($carreira);
    $this->getSalario();
  }

  public function setNome(string $nome) {
    $this->nome = $nome;
  }

  public function getNome() {
    return $this->nome;
  }

  public function setCarreira(string $carreira) {
    $this->carreira = $carreira;
  }

  public function getSalario() {
    switch ($this->carreira) {
      case 'gerente1':
        $this->salario = 4000.00;
        break;
      case 'gerente2':
        $this->salario = 5000.00;
        break;
      case 'gerente3':
        $this->salario = 8000.00;
        break;
      default:
        throw new Exception('Carreira inválida: '.$this->carreira);
    }

    return $this->salario;
  }
}

class Funcionario {
  public $nome;
  private $cargo;

  public function __construct(string $nome, InterfaceCargo $cargo) {
    $this->nome  = $nome;
    $this->cargo = $cargo;
  }

  public function calculaSalario() {
    return $this->cargo->getSalario();
  }
}

$henzo = new Funcionario('Henzo Gomes', new Desenvolvedor('fullstack2'));
$fulano = new Funcionario('José da Silva', new Gestao('gerente3'));

echo "<pre>";
print_r($henzo);
echo "</pre>";
echo "O salário de ".$henzo->nome ." é de: ".$henzo->calculaSalario();

echo "<pre>"; print_r($fulano); echo "</pre>";
echo "O salário de ".$fulano->nome ." é de: ".$fulano->calculaSalario();


/*
Funcionario Object
(
    [nome] => Henzo Gomes
    [cargo:Funcionario:private] => Desenvolvedor Object
        (
            [nome:Desenvolvedor:private] => Desenvolvedor
            [carreira:Desenvolvedor:private] => fullstack2
            [salario:Desenvolvedor:private] => 3000
        )

)
Salário de Henzo Gomes é de: 3000

Funcionario Object
(
    [nome] => José da Silva
    [cargo:Funcionario:private] => Gestao Object
        (
            [nome:Gestao:private] => Gestão
            [carreira:Gestao:private] => gerente3
            [salario:Gestao:private] => 8000
        )

)
Salário de José da Silva é de: 8000
*/
