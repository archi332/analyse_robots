<?php

/**
 * Created by PhpStorm.
 * User: AE
 * Date: 13.07.16
 * Time: 15:27
 */
class analyse_robots
{
    protected $_url = '';
    public $host = array();
    public $sitemap = false;

    /**
     * @return array
     */
    function getHeaders()
    {
        if($headers = get_headers($this->_url, 1)){
            return $headers;
        } else {
            return false;
        }
    }

    /**
     * @return mixed
     */
    function getResponse()
    {
        return $this->getHeaders()['0'];
    }


    function openFile()
    {
        if(!fopen($this->_url, "r")){
            return false;
            exit;
        }
    }

    /**
     * get host & sitemap
     * @return void
     */
    function getHostSitemap()
    {



        $robots_array = file($this->_url);
        foreach($robots_array as $string){


            $pos = strpos($string, 'Host');
            if ($pos !== false) {
                $this->host[] =  true;
            }
            $pos = strpos($string, 'Sitemap');
            if ($pos !== false) {
//                echo "<br>Строка \'Sitemap\' найдена в строке '$string'";
                $this->sitemap =  true;
            }

        }
    }


    /**
     * get size of file robots.txt
     * @return void
     */
    function getSize()
    {
        return $this->getHeaders()['Content-Length'];

    }

    /**
     * @return int|string
     */
    function getResponseCode()
    {
        $response = $this->getResponse();

        $response = explode(' ', $response);

        foreach($response as $val){
            if (is_numeric($val)){
                $response_code = $val;
            }
        }
        return $response_code;
    }


    function issetRobotsFormat()
    {
        $this->result['isset_robots']['check_name'] = 'Проверка наличия файла robots.txt';
        $this->result['isset_robots']['und1'] = 'Состояние';
        $this->result['isset_robots']['und2'] = 'Рекомендации';

        if ($this->openFile() !== false){
            $this->result['isset_robots']['status'] = 'OK';
            $this->result['isset_robots']['condition1'] = 'Файл robots.txt присутствует по данному адресу';
            $this->result['isset_robots']['condition2'] = 'Доработки не требуются';
        } else {
            $this->result['isset_robots']['status'] = 'Ошибка';
            $this->result['isset_robots']['condition1'] = 'Файл robots.txt отсутствует';
            $this->result['isset_robots']['condition2'] = 'Программист: Создать файл robots.txt и разместить его на сайте.';
            return false;
        }
    }

    function issetHost()
    {
        $this->result['isset_host']['check_name'] = 'Проверка указания директивы Host';

        if ($this->host['0']) {
            $this->result['isset_host']['status'] = 'OK';
            $this->result['isset_host']['condition1'] = 'Директива Host указана';
            $this->result['isset_host']['condition2'] = 'Доработки не требуются';
        } else {
            $this->result['isset_host']['status'] = 'Ошибка';
            $this->result['isset_host']['condition1'] = 'В файле robots.txt не указана директива Host';
            $this->result['isset_host']['condition2'] = 'Программист: Для того, чтобы поисковые системы знали, какая версия сайта является основных зеркалом, необходимо прописать адрес основного зеркала в директиве Host. В данный момент это не прописано. Необходимо добавить в файл robots.txt директиву Host. Директива Host задётся в файле 1 раз, после всех правил.';

            return false;
        }
    }


    function countHost()
    {
        $count = count($this->host);

        $this->result['count_host']['check_name'] = 'Проверка количества директив Host, прописанных в файле	robots.txt';

        if ($count == 1) {

            $this->result['count_host']['status'] = 'OK';
            $this->result['count_host']['condition1'] = 'В файле прописана 1 директива Host';
            $this->result['count_host']['condition2'] = 'Доработки не требуются';
        } else {
            $this->result['count_host']['status'] = 'Ошибка';
            $this->result['count_host']['condition1'] = 'В файле прописано несколько директив Host';
            $this->result['count_host']['condition2'] = 'Программист: Директива Host должна быть указана в файле толоко 1 раз. Необходимо удалить все дополнительные директивы Host и оставить только 1, корректную и соответствующую основному зеркалу сайта.';
        }
    }

    function sizeRobots()
    {
        $this->result['robots_size']['check_name'] = 'Проверка размера файла robots.txt';

        $size = $this->getSize() / 1024;

        if ($size < 32 && $size > 0) {

            $this->result['robots_size']['status'] = 'OK';
            $this->result['robots_size']['condition1'] = 'Размер файла robots.txt составляет ' . $size . ' kb, что находится в пределах допустимой нормы';
            $this->result['robots_size']['condition2'] = 'Доработки не требуются';
        } else {
            $this->result['robots_size']['status'] = 'Ошибка';
            $this->result['robots_size']['condition1'] = 'Размера файла robots.txt составляет ' . $size . ' kb, что превышает допустимую норму';
            $this->result['robots_size']['condition2'] = 'Программист: Максимально допустимый размер файла robots.txt составляем 32 кб. Необходимо отредактировть файл robots.txt таким образом, чтобы его размер не превышал 32 Кб.';
        }
    }


    function issetSitemap()
    {
        $this->result['isset_Sitemap']['check_name'] = 'Проверка указания директивы Sitemap';

        if ($this->sitemap == true) {

            $this->result['isset_Sitemap']['status'] = 'OK';
            $this->result['isset_Sitemap']['condition1'] = 'Директива Sitemap указана';
            $this->result['isset_Sitemap']['condition2'] = 'Доработки не требуются';
        } else {
            $this->result['isset_Sitemap']['status'] = 'Ошибка';
            $this->result['isset_Sitemap']['condition1'] = 'В файле robots.txt не указана директива Sitemap';
            $this->result['isset_Sitemap']['condition2'] = 'Программист: Добавить в файл robots.txt директиву Sitemap';
        }
    }


    function responseCode()
    {
        $this->result['response_code']['check_name'] = 'Проверка кода ответа сервера для файла robots.txt';

        if ($this->getResponseCode() == 200) {

            $this->result['response_code']['status'] = 'OK';
            $this->result['response_code']['condition1'] = 'Файл robots.txt отдаёт код ответа сервера 200';
            $this->result['response_code']['condition2'] = 'Доработки не требуются';
        } else {
            $this->result['response_code']['status'] = 'Ошибка';
            $this->result['response_code']['condition1'] = 'При обращении к файлу robots.txt сервер возвращает код ответа ' . $this->getResponseCode();
            $this->result['response_code']['condition2'] = 'Программист: Файл robots.txt должны отдавать код ответа 200, иначе файл не будет обрабатываться. Необходимо настроить сайт таким образом, чтобы при обращении к файлу sitemap.xml сервер возвращает код ответа 200';
        }
    }


    function getResultFormat($url)
    {

        $this->validationUrl($url);



        //formated:
        if ($this->issetRobotsFormat() === false) {

        } else {
            $this->getHostSitemap();
            if($this->issetHost() !== false) {
                $this->countHost();
            }
            $this->sizeRobots();
            $this->issetSitemap();

            $this->responseCode();
        }

        echo "
    <table border='1'>

    <tr>
        <td><b>№</b></td>
        <td><b>Название проверки</b></td>
        <td><b>Статус</b></td>
        <td><b>сост\рек</b></td>
        <td><b>Текущее состояние</b></td>
    </tr>";

        $i = 0;
        foreach ($this->result as $value) {
            if (is_array($value)) {
                $i++;
                echo "
                <tr>
                    <td rowspan=2>$i</td>
                    <td rowspan=2>$value[check_name]</td>
                    <td rowspan=2>$value[status]</td>
                    <td>Состояние</td>
                    <td>$value[condition1]</td>
                </tr>
                <tr>
                    <td>Рекомендации</td>
                    <td>$value[condition2]</td>
                </tr>
                ";
            }
        }
        echo "</table>";
    }


    function validationUrl($url)
    {
        if($url != null){
            $this->_url = $url;
        } else {
            echo '<br>Please, write in an address!';
            exit();
        }

        if($this->getHeaders() == false){
            echo '<br>Not correct url, can\'t open file';
            exit();
        }
    }

}