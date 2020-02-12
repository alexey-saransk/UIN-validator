<?php

/**
 * Ver.1.0
 *
 * Copyright © 2020 Alexey Akashkin
 * Contacts: <id41864819@gmail.com>
 * License: http://opensource.org/licenses/MIT
 *
 * Если УИН адекватный, то возвращаем массив, где code=Y и text=Y
 * А если проблемный, то возвращаем массив где будет code ошибки и text ошибки
 *
 * http://fssprus.ru/iss/ip - здесь можно поискать УИНы для тестов
 *
 * пример: 18810013160000839240 - дважды делится на 10
 *
 */

namespace evolenta\uinValidator;

class uinValidator
{

	public static function uinValidate($uin) {

		if (strlen($uin) !== 20 && strlen($uin) !== 25) {
			$message["code"] = 1;
			$message["text"] = "УИН должен быть на 20 или 25 символов";
			return $message;
		} elseif (!ctype_digit($uin)) {
			$message["code"] = 2;
			$message["text"] = "УИН должен содержать только цифры";
			return $message;
		} elseif ($uin == "00000000000000000000") {
			$message["code"] = 3;
			$message["text"] = "Такой УИН использовать нельзя";
			return $message;
		}

		//Разбивает строку УИН на массив
		$arrNumbers = preg_split('//', $uin, -1, PREG_SPLIT_NO_EMPTY);

		// последний элемент массива - контрольный разряд
		$lastChar = end($arrNumbers);

		$controlSumAndRound = self::getControlAndRound($arrNumbers, 1, 0);

		$controlSumAndRound = explode(",", $controlSumAndRound);

		// $controlSumAndRound[0] - Sum
		// $controlSumAndRound[1] - Round

		if ($controlSumAndRound[1] == 1 && ($controlSumAndRound[0] % 11) !== 10) {

			//Если раунд 1 и контрольный разряд не 10
			$controlChar = $controlSumAndRound[0] % 11;
			$round = 1;

		} elseif ($controlSumAndRound[1] == 1 && ($controlSumAndRound[0] % 11) == 10) {

			//Если раунд 1 и контрольный разряд 10
			$controlSumAndRound = self::getControlAndRound($arrNumbers, 2, 2);
			$controlSumAndRound_2 = explode(",", $controlSumAndRound);

			$controlChar = $controlSumAndRound_2[0] % 11;
			$round = 2;

		}

		return self::finish($controlChar, $lastChar, $round);

	}

	protected static function getControlAndRound($arrNumbers, $round, $shiftLeft) {

		// Убираем последний элемент, т.к. он последний разряд
		array_pop($arrNumbers);

		$controlSum = 0;

		for ($i = 0; $i < count($arrNumbers); $i++) {

			// $vesNumbers = Число из набора весов натурального ряда чисел
			// 1, 2, 3, ... 10, 1, 2 ...
			$vesNumbers = (($i + $shiftLeft) % 10) + 1;

			$controlSum += $arrNumbers[$i] * $vesNumbers;
		}

		return $controlSum.",".$round;

	}


	protected static function finish($controlChar, $lastChar, $round) {

		$arrValid = [];
		for ($i = 0; $i < 10; $i++) {
			array_push($arrValid, $i);
		}

		if (in_array($controlChar, $arrValid, true)  && $controlChar == $lastChar) {
			$message["code"] = "Y";
			$message["text"] = "Y";
		} else {
			$message["code"] = "N";
			$message["text"] = "УИН номер не валидный";
		}

		// тот самый случай, когда при повторном расчёте остаток от деления опять 10
		if ($round == 2 && $controlChar == 10) {
			$message["code"] = "Y";
			$message["text"] = "Y";
		}

		return $message;

	}

}