<?php

namespace App\Service\Validations;

class identificationDocument {

        function validateCPF($cpf) {
                // Extract only the numbers
                $cpf = preg_replace( '/[^0-9]/is', '', $cpf );
                
                // Checks if all digits were entered correctly
                if (strlen($cpf) != 11) {
                    return false;
                }
        
                // Checks for a sequence of repeating digits. Ex: 111.111.111-11
                if (preg_match('/(\d)\1{10}/', $cpf)) {
                    return false;
                }
        
                // calculation to validate the CPF
                for ($t = 9; $t < 11; $t++) {
                    for ($d = 0, $c = 0; $c < $t; $c++) {
                        $d += $cpf[$c] * (($t + 1) - $c);
                    }
                    $d = ((10 * $d) % 11) % 10;
                    if ($cpf[$c] != $d) {
                        return false;
                    }
                }
                return true;
            }
        
        function validateCNPJ($cnpj) {
            
                $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
                
                // Validate size
                if (strlen($cnpj) != 14)
                    return false;
        
                // Checks if all digits are the same
                if (preg_match('/(\d)\1{13}/', $cnpj))
                    return false;	
        
                // Validates first check digit
                for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
                {
                    $soma += $cnpj[$i] * $j;
                    $j = ($j == 2) ? 9 : $j - 1;
                }
        
                $resto = $soma % 11;
        
                if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
                    return false;
        
                // Validate second check digit
                for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
                {
                    $soma += $cnpj[$i] * $j;
                    $j = ($j == 2) ? 9 : $j - 1;
                }
        
                $resto = $soma % 11;
        
                return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
            }
}
