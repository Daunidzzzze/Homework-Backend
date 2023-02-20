<?php
// Шаг 1: Получить номер карты из ввода консоли
$card_number = fgets(STDIN);

// Шаг 2: Удалить все нечисловые символы из номера карты
$card_number = preg_replace('/\D/', '', $card_number);

// Шаг 3: Сравнить номер карты с регулярными выражениями, чтобы определить выпустившую банковскую организацию
if (preg_match('/^5[1-5]\d{14}$|^62\d{14}$|^67\d{14}$/', $card_number)) {
    $card_issuer = "Mastercard";
} elseif (preg_match('/^4[0-9]\d{12,18}$|^14\d{12,18}$/', $card_number)) {
    $card_issuer = "Visa";
} else {
    $card_issuer = "Unknown";
}

// Шаг 5: Рассчитать контрольную сумму номера карты с помощью алгоритма Луна
$checksum = 0;
$length = strlen($card_number);
for ($i = 1; $i <= $length; $i++) {
    $digit = substr($card_number, -$i, 1);
    if ($i % 2 == 0) {
        $double = $digit * 2;
        $checksum += ($double < 10) ? $double : ($double - 9);
    } else {
        $checksum += $digit;
    }
}

// Шаг 6: Вывести номер карты, выпустившую банковскую организацию и информацию о том, является ли номер карты действительным или недействительным на основе расчета контрольной суммы
echo "Номер карты: $card_number\n";
echo "Банковская организация: $card_issuer\n";
if ($checksum % 10 == 0) {
    echo "Контрольная сумма действительна\n";
} else {
    echo "Контрольная сумма недействительна\n";
}