<?php
class CreditCardValidator
{
    public static function validate($cardNumber)
    {
        // Удаляем все нецифровые символы из номера карты
        $cardNumber = preg_replace('/\D/', '', $cardNumber);

        // Проверяем, что длина номера карты соответствует стандарту ISO/IEC 7812
        $cardLength = strlen($cardNumber);
        if ($cardLength < 13 || $cardLength > 19) {
            return false;
        }

        // Проверяем, что номер карты проходит алгоритм Луна
        $sum = 0;
        for ($i = $cardLength - 1; $i >= 0; $i--) {
            $digit = (int) $cardNumber[$i];
            if (($cardLength - $i) % 2 == 0) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }
        if ($sum % 10 != 0) {
            return false;
        }

        // Определяем эмитенту карты по префиксу
        $prefix = substr($cardNumber, 0, 2);
        if (in_array($prefix, range(51, 55)) || $prefix == 62 || $prefix == 67) {
            return 'MasterCard';
        }
        if (in_array($prefix, range(40, 49)) || $prefix == 14) {
            return 'VISA';
        }

        // Если эмитент карты не распознан, возвращаем false
        return false;
    }
}

// Читаем номер карты из консоли
echo 'Введите номер кредитной карты: ';
$cardNumber = trim(fgets(STDIN));

// Проверяем номер карты
$issuer = CreditCardValidator::validate($cardNumber);
if ($issuer) {
    echo "Действительная карта $issuer\n";
} else {
    echo "Неверный номер карты\n";
}