# UIN-validator

**Правила расчёта контрольного разряда УИН.**

Контрольный разряд УИН формируется по следующим правилам:
+ каждому разряду УИН, начиная со старшего разряда, присваивается набор весов, соответствующий натуральному ряду чисел от 1 до 10, далее набор весов повторяется;
+ каждая цифра УИН умножается на присвоенный вес разряда и вычисляется сумма полученных произведений;
+ контрольный разряд для УИН представляет собой остаток от деления полученной суммы на модуль «11». Контрольный разряд должен иметь значение от 0 до 9;
+ если получается остаток, равный 10, то для обеспечения одноразрядного контрольного разряда необходимо провести повторный расчет, применяя вторую последовательность весов, являющуюся результатом циклического сдвига исходной последовательности на два разряда влево (3, 4, 5, 6, 7, 8, 9, 10, 1, 2). Если, в случае повторного расчета, остаток от деления вновь сохраняется равным 10, то значение контрольного разряда проставляется равным «0».

`uinValidator.php` - Вариант с ООП

`uinValidator_NoOOP.php` - Вариант без ООП

Если УИН нормальный, то возвращаем массив, где code=Y и text=Y, а если проблемный, то возвращаем массив, где будет code ошибки и text ошибки.

Изначально валидатор создавался для бэка, но для того чтобы использовать и на фронте(JS) создайте и отправляйте AJAX запросы на ajaxUinValidator.php (пример чуть ниже)

```javascript
    // *.js - для ajax-запросов
    let number = "18810102180511993457";
    let url = ".../uinValidator.php";

    let data = {
        "uin": number
    };

    $.ajax({
        type: "POST",
        url: url,
        data: JSON.stringify(data),
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function (data) {
            console.log("Результат: ", data);
            if (data.code == "Y" && data.text == "Y") {
                alert("УИН валидный");
            } else {
                alert("УИН НЕ валидный");
            }
        },
    });
```

```php
<?php 
    
    //создаём ajaxUinValidator.php - Вариант с ООП
    //сам файл uinValidator.php находился в #DOCUMENT_ROOT#/vendor/evolenta/uinValidator/uinValidator.php
    
    require_once($_SERVER["DOCUMENT_ROOT"].'/vendor/autoload.php');
    
    $uin = json_decode(file_get_contents('php://input'), true);
    
    $result = evolenta\uinValidator\uinValidator::uinIsValidate($uin);
    
    echo json_encode($result);

?>
```
    
 ```php
 <?php 
 
    // Для варианта без ООП просто используем uinValidator_NoOOP.php
 
 ?>
 ```

В любом случае пишите фидбэк или замечания если что-то не так