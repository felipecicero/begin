<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Translate
 * @subpackage Ressource
 * @copyright  Copyright (c) 2005-2011 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id:$
 */

/**
 * EN-Revision: 22075
 */
return array(
    // Zend_Validate_Alnum
    "Invalid type given, value should be float, string, or integer" => "O tipo especificado � inv�lido, o valor deve ser float, string, ou inteiro",
    "'%value%' contains characters which are non alphabetic and no digits" => "'%value%' cont�m caracteres que n�o s�o alfab�ticos e nem d�gitos",
    "'%value%' is an empty string" => "'%value%' � uma string vazia",

    // Zend_Validate_Alpha
    "Invalid type given, value should be a string" => "O tipo especificado � inv�lido, o valor deve ser uma string",
    "'%value%' contains non alphabetic characters" => "'%value%' cont�m caracteres n�o alfab�ticos",
    "'%value%' is an empty string" => "'%value%' � uma string vazia",

    // Zend_Validate_Barcode
    "'%value%' failed checksum validation" => "'%value%' falhou na valida��o do checksum",
    "'%value%' contains invalid characters" => "'%value%' cont�m caracteres inv�lidos",
    "'%value%' should have a length of %length% characters" => "'%value%' tem um comprimento de %length% caracteres",
    "Invalid type given, value should be string" => "O tipo especificado � inv�lido, o valor deve ser string",

    // Zend_Validate_Between
    "'%value%' is not between '%min%' and '%max%', inclusively" => "'%value%' n�o est� entre '%min%' e '%max%', inclusivamente",
    "'%value%' is not strictly between '%min%' and '%max%'" => "'%value%' n�o est� exatamente entre '%min%' e '%max%'",

    // Zend_Validate_Callback
    "'%value%' is not valid" => "'%value%' n�o � v�lido",
    "Failure within the callback, exception returned" => "Falha na chamada de retorno, exce��o retornada",

    // Zend_Validate_Ccnum
    "'%value%' must contain between 13 and 19 digits" => "'%value%' deve conter entre 13 e 19 d�gitos",
    "Luhn algorithm (mod-10 checksum) failed on '%value%'" => "O algoritmo de Luhn (checksum de m�dulo 10) falhou em '%value%'",

    // Zend_Validate_CreditCard
    "Luhn algorithm (mod-10 checksum) failed on '%value%'" => "O algoritmo de Luhn (checksum de m�dulo 10) falhou em '%value%'",
    "'%value%' must contain only digits" => "'%value%' deve conter apenas d�gitos",
    "Invalid type given, value should be a string" => "O tipo especificado � inv�lido, o valor deve ser uma string",
    "'%value%' contains an invalid amount of digits" => "'%value%' cont�m uma quantidade inv�lida de d�gitos",
    "'%value%' is not from an allowed institute" => "'%value%' n�o vem de uma institui��o autorizada",
    "Validation of '%value%' has been failed by the service" => "A valida��o de '%value%' falhou por causa do servi�o",
    "The service returned a failure while validating '%value%'" => "O servi�o devolveu um erro enquanto validava '%value%'",

    // Zend_Validate_Date
    "Invalid type given, value should be string, integer, array or Zend_Date" => "O tipo especificado � inv�lido, o valor deve ser string, inteiro, matriz ou Zend_Date",
    "'%value%' does not appear to be a valid date" => "'%value%' n�o parece ser uma data v�lida",
    "'%value%' does not fit the date format '%format%'" => "'%value%' n�o se encaixa no formato de data '%format%'",

    // Zend_Validate_Db_Abstract
    "No record matching %value% was found" => "N�o foram encontrados registros para %value%",
    "A record matching %value% was found" => "Um registro foi encontrado para %value%",

    // Zend_Validate_Digits
    "Invalid type given, value should be string, integer or float" => "O tipo especificado � inv�lido, o valor deve ser string, inteiro ou float",
    "'%value%' contains characters which are not digits; but only digits are allowed" => "'%value%' cont�m caracteres que n�o s�o d�gitos, mas apenas d�gitos s�o permitidos",
    "'%value%' is an empty string" => "'%value%' � uma string vazia",

    // Zend_Validate_EmailAddress
    "Invalid type given, value should be a string" => "O tipo especificado � inv�lido, o valor deve ser uma string",
    "'%value%' is no valid email address in the basic format local-part@hostname" => "'%value%' n�o � um endere�o de e-mail v�lido no formato local-part@hostname",
    "'%hostname%' is no valid hostname for email address '%value%'" => "'%hostname%' n�o � um nome de host v�lido para o endere�o de e-mail '%value%'",
    "'%hostname%' does not appear to have a valid MX record for the email address '%value%'" => "'%hostname%' n�o parece ter um registro MX v�lido para o endere�o de e-mail '%value%'",
    "'%hostname%' is not in a routable network segment. The email address '%value%' should not be resolved from public network." => "'%hostname%' n�o � um segmento de rede rote�vel. O endere�o de e-mail '%value%' n�o deve ser resolvido a partir de um rede p�blica.",
    "'%localPart%' can not be matched against dot-atom format" => "'%localPart%' n�o corresponde com o formato dot-atom",
    "'%localPart%' can not be matched against quoted-string format" => "'%localPart%' n�o corresponde com o formato quoted-string",
    "'%localPart%' is no valid local part for email address '%value%'" => "'%localPart%' n�o � uma parte local v�lida para o endere�o de e-mail '%value%'",
    "'%value%' exceeds the allowed length" => "'%value%' excede o comprimento permitido",

    // Zend_Validate_File_Count
    "Too many files, maximum '%max%' are allowed but '%count%' are given" => "H� muitos arquivos, s�o permitidos no m�ximo '%max%', mas '%count%' foram fornecidos",
    "Too few files, minimum '%min%' are expected but '%count%' are given" => "H� poucos arquivos, s�o esperados no m�nimo '%min%', mas '%count%' foram fornecidos",

    // Zend_Validate_File_Crc32
    "File '%value%' does not match the given crc32 hashes" => "O arquivo '%value%' n�o corresponde ao hash crc32 fornecido",
    "A crc32 hash could not be evaluated for the given file" => "N�o foi poss�vel avaliar um hash crc32 para o arquivo fornecido",
    "File '%value%' could not be found" => "O arquivo '%value%' n�o p�de ser encontrado",

    // Zend_Validate_File_ExcludeExtension
    "File '%value%' has a false extension" => "O arquivo '%value%' possui a extens�o incorreta",
    "File '%value%' could not be found" => "O arquivo '%value%' n�o p�de ser encontrado",

    // Zend_Validate_File_ExcludeMimeType
    "File '%value%' has a false mimetype of '%type%'" => "O arquivo '%value%' tem o mimetype incorreto: '%type%'",
    "The mimetype of file '%value%' could not be detected" => "O mimetype do arquivo '%value%' n�o p�de ser detectado",
    "File '%value%' can not be read" => "O arquivo '%value%' n�o p�de ser lido",

    // Zend_Validate_File_Exists
    "File '%value%' does not exist" => "O arquivo '%value%' n�o existe",

    // Zend_Validate_File_Extension
    "File '%value%' has a false extension" => "O arquivo '%value%' possui a extens�o incorreta",
    "File '%value%' could not be found" => "O arquivo '%value%' n�o p�de ser encontrado",

    // Zend_Validate_File_FilesSize
    "All files in sum should have a maximum size of '%max%' but '%size%' were detected" => "Todos os arquivos devem ter um tamanho m�ximo de '%max%', mas um tamanho de '%size%' foi detectado",
    "All files in sum should have a minimum size of '%min%' but '%size%' were detected" => "Todos os arquivos devem ter um tamanho m�nimo de '%min%', mas um tamanho de '%size%' foi detectado",
    "One or more files can not be read" => "Um ou mais arquivos n�o puderam ser lidos",

    // Zend_Validate_File_Hash
    "File '%value%' does not match the given hashes" => "O arquivo '%value%' n�o corresponde ao hash fornecido",
    "A hash could not be evaluated for the given file" => "N�o foi poss�vel avaliar um hash para o arquivo fornecido",
    "File '%value%' could not be found" => "O arquivo '%value%' n�o p�de ser encontrado",

    // Zend_Validate_File_ImageSize
    "Maximum allowed width for image '%value%' should be '%maxwidth%' but '%width%' detected" => "A largura m�xima permitida para a imagem '%value%' deve ser '%maxwidth%', mas '%width%' foi detectada",
    "Minimum expected width for image '%value%' should be '%minwidth%' but '%width%' detected" => "A largura m�nima esperada para a imagem '%value%' deve ser '%minwidth%', mas '%width%' foi detectada",
    "Maximum allowed height for image '%value%' should be '%maxheight%' but '%height%' detected" => "A altura m�xima permitida para a imagem '%value%' deve ser '%maxheight%', mas '%height%' foi detectada",
    "Minimum expected height for image '%value%' should be '%minheight%' but '%height%' detected" => "A altura m�nima esperada para a imagem '%value%' deve ser '%minheight%', mas '%height%' foi detectada",
    "The size of image '%value%' could not be detected" => "O tamanho da imagem '%value%' n�o p�de ser detectado",
    "File '%value%' can not be read" => "O arquivo '%value%' n�o p�de ser lido",

    // Zend_Validate_File_IsCompressed
    "File '%value%' is not compressed, '%type%' detected" => "O arquivo '%value%' n�o est� compactado: '%type%' detectado",
    "The mimetype of file '%value%' could not be detected" => "O mimetype do arquivo '%value%' n�o p�de ser detectado",
    "File '%value%' can not be read" => "O arquivo '%value%' n�o p�de ser lido",

    // Zend_Validate_File_IsImage
    "File '%value%' is no image, '%type%' detected" => "O arquivo '%value%' n�o � uma imagem: '%type%' detectado",
    "The mimetype of file '%value%' could not be detected" => "O mimetype do arquivo '%value%' n�o p�de ser detectado",
    "File '%value%' can not be read" => "O arquivo '%value%' n�o p�de ser lido",

    // Zend_Validate_File_Md5
    "File '%value%' does not match the given md5 hashes" => "O arquivo '%value%' n�o corresponde ao hash md5 fornecido",
    "A md5 hash could not be evaluated for the given file" => "N�o foi poss�vel avaliar um hash md5 para o arquivo fornecido",
    "File '%value%' could not be found" => "O arquivo '%value%' n�o p�de ser encontrado",

    // Zend_Validate_File_MimeType
    "File '%value%' has a false mimetype of '%type%'" => "O arquivo '%value%' tem o mimetype incorreto: '%type%'",
    "The mimetype of file '%value%' could not be detected" => "O mimetype do arquivo '%value%' n�o p�de ser detectado",
    "File '%value%' can not be read" => "O arquivo '%value%' n�o p�de ser lido",

    // Zend_Validate_File_NotExists
    "File '%value%' exists" => "O arquivo '%value%' existe",

    // Zend_Validate_File_Sha1
    "File '%value%' does not match the given sha1 hashes" => "O arquivo '%value%' n�o corresponde ao hash sha1 fornecido",
    "A sha1 hash could not be evaluated for the given file" => "N�o foi poss�vel avaliar um hash sha1 para o arquivo fornecido",
    "File '%value%' could not be found" => "O arquivo '%value%' n�o p�de ser encontrado",

    // Zend_Validate_File_Size
    "Maximum allowed size for file '%value%' is '%max%' but '%size%' detected" => "O tamanho m�ximo permitido para o arquivo '%value%' � '%max%', mas '%size%' foram detectados",
    "Minimum expected size for file '%value%' is '%min%' but '%size%' detected" => "O tamanho m�nimo esperado para o arquivo '%value%' � '%min%', mas '%size%' foram detectados",
    "File '%value%' could not be found" => "O arquivo '%value%' n�o p�de ser encontrado",

    // Zend_Validate_File_Upload
    "File '%value%' exceeds the defined ini size" => "O arquivo '%value%' excede o tamanho definido na configura��o",
    "File '%value%' exceeds the defined form size" => "O arquivo '%value%' excede o tamanho definido do formul�rio",
    "File '%value%' was only partially uploaded" => "O arquivo '%value%' foi apenas parcialmente enviado",
    "File '%value%' was not uploaded" => "O arquivo '%value%' n�o foi enviado",
    "No temporary directory was found for file '%value%'" => "Nenhum diret�rio tempor�rio foi encontrado para o arquivo '%value%'",
    "File '%value%' can't be written" => "O arquivo '%value%' n�o p�de ser escrito",
    "A PHP extension returned an error while uploading the file '%value%'" => "Uma extens�o do PHP retornou um erro enquanto o arquivo '%value%' era enviado",
    "File '%value%' was illegally uploaded. This could be a possible attack" => "O arquivo '%value%' foi enviado ilegalmente. Este poderia ser um poss�vel ataque",
    "File '%value%' was not found" => "O arquivo '%value%' n�o foi encontrado",
    "Unknown error while uploading file '%value%'" => "Erro desconhecido ao enviar o arquivo '%value%'",

    // Zend_Validate_File_WordCount
    "Too much words, maximum '%max%' are allowed but '%count%' were counted" => "H� muitas palavras, s�o permitidas no m�ximo '%max%', mas '%count%' foram contadas",
    "Too less words, minimum '%min%' are expected but '%count%' were counted" => "H� poucas palavras, s�o esperadas no m�nimo '%min%', mas '%count%' foram contadas",
    "File '%value%' could not be found" => "O arquivo '%value%' n�o p�de ser encontrado",

    // Zend_Validate_Float
    "Invalid type given, value should be float, string, or integer" => "O tipo especificado � inv�lido, o valor deve ser float, string, ou inteiro",
    "'%value%' does not appear to be a float" => "'%value%' n�o parece ser um float",

    // Zend_Validate_GreaterThan
    "'%value%' is not greater than '%min%'" => "'%value%' n�o � maior que '%min%'",

    // Zend_Validate_Hex
    "Invalid type given, value should be a string" => "O tipo especificado � inv�lido, o valor deve ser uma string",
    "'%value%' has not only hexadecimal digit characters" => "'%value%' n�o cont�m somente caracteres hexadecimais",

    // Zend_Validate_Hostname
    "Invalid type given, value should be a string" => "O tipo especificado � inv�lido, o valor deve ser uma string",
    "'%value%' appears to be an IP address, but IP addresses are not allowed" => "'%value%' parece ser um endere�o de IP, mas endere�os de IP n�o s�o permitidos",
    "'%value%' appears to be a DNS hostname but cannot match TLD against known list" => "'%value%' parece ser um hostname de DNS, mas o TLD n�o corresponde a nenhum TLD conhecido",
    "'%value%' appears to be a DNS hostname but contains a dash in an invalid position" => "'%value%' parece ser um hostname de DNS, mas cont�m um tra�o em uma posi��o inv�lida",
    "'%value%' appears to be a DNS hostname but cannot match against hostname schema for TLD '%tld%'" => "'%value%' parece ser um hostname de DNS, mas n�o corresponde ao esquema de hostname para o TLD '%tld%'",
    "'%value%' appears to be a DNS hostname but cannot extract TLD part" => "'%value%' parece ser um hostname de DNS, mas o TLD n�o p�de ser extra�do",
    "'%value%' does not match the expected structure for a DNS hostname" => "'%value%' n�o corresponde com a estrutura esperada para um hostname de DNS",
    "'%value%' does not appear to be a valid local network name" => "'%value%' n�o parece ser um nome de rede local v�lido",
    "'%value%' appears to be a local network name but local network names are not allowed" => "'%value%' parece ser um nome de rede local, mas os nomes de rede local n�o s�o permitidos",
    "'%value%' appears to be a DNS hostname but the given punycode notation cannot be decoded" => "'%value%' parece ser um hostname de DNS, mas a nota��o punycode fornecida n�o pode ser decodificada",

    // Zend_Validate_Iban
    "Unknown country within the IBAN '%value%'" => "Pa�s desconhecido para o IBAN '%value%'",
    "'%value%' has a false IBAN format" => "'%value%' n�o � um formato IBAN v�lido",
    "'%value%' has failed the IBAN check" => "'%value%' falhou na verifica��o do IBAN",

    // Zend_Validate_Identical
    "The two given tokens do not match" => "Os dois tokens fornecidos n�o combinam",
    "No token was provided to match against" => "Nenhum token foi fornecido para a compara��o",

    // Zend_Validate_InArray
    "'%value%' was not found in the haystack" => "'%value%' n�o faz parte dos valores esperados",

    // Zend_Validate_Int
    "Invalid type given, value should be string or integer" => "O tipo especificado � inv�lido, o valor deve ser string ou inteiro",
    "'%value%' does not appear to be an integer" => "'%value%' n�o parece ser um n�mero inteiro",

    // Zend_Validate_Ip
    "Invalid type given, value should be a string" => "O tipo especificado � inv�lido, o valor deve ser uma string",
    "'%value%' does not appear to be a valid IP address" => "'%value%' n�o parece ser um endere�o de IP v�lido",

    // Zend_Validate_Isbn
    "Invalid type given, value should be string or integer" => "O tipo especificado � inv�lido, o valor deve ser string ou inteiro",
    "'%value%' is no valid ISBN number" => "'%value%' n�o � um n�mero ISBN v�lido",

    // Zend_Validate_LessThan
    "'%value%' is not less than '%max%'" => "'%value%' n�o � menor que '%max%'",

    // Zend_Validate_NotEmpty
    "Invalid type given, value should be float, string, array, boolean or integer" => "O tipo especificado � inv�lido, o valor deve ser float, string, matriz, booleano ou inteiro",
    "Value is required and can't be empty" => "O valor � obrigat�rio e n�o pode estar vazio",

    // Zend_Validate_PostCode
    "Invalid type given. The value should be a string or a integer" => "O tipo especificado � inv�lido. O valor deve ser uma string ou um inteiro",
    "'%value%' does not appear to be a postal code" => "'%value%' n�o parece ser um c�digo postal",

    // Zend_Validate_Regex
    "Invalid type given, value should be string, integer or float" => "O tipo especificado � inv�lido, o valor deve ser string, inteiro ou float",
    "'%value%' does not match against pattern '%pattern%'" => "'%value%' n�o corresponde ao padr�o '%pattern%'",
    "There was an internal error while using the pattern '%pattern%'" => "Houve um erro interno durante o uso do padr�o '%pattern%'",

    // Zend_Validate_Sitemap_Changefreq
    "'%value%' is no valid sitemap changefreq" => "'%value%' n�o � um changefreq de sitemap v�lido",
    "Invalid type given, the value should be a string" => "O tipo especificado � inv�lido, o valor deve ser uma string",

    // Zend_Validate_Sitemap_Lastmod
    "'%value%' is no valid sitemap lastmod" => "'%value%' n�o � um lastmod de sitemap v�lido",
    "Invalid type given, the value should be a string" => "O tipo especificado � inv�lido, o valor deve ser uma string",

    // Zend_Validate_Sitemap_Loc
    "'%value%' is no valid sitemap location" => "'%value%' n�o � uma localiza��o de sitemap v�lida",
    "Invalid type given, the value should be a string" => "O tipo especificado � inv�lido, o valor deve ser uma string",

    // Zend_Validate_Sitemap_Priority
    "'%value%' is no valid sitemap priority" => "'%value%' n�o � uma prioridade de sitemap v�lida",
    "Invalid type given, the value should be a integer, a float or a numeric string" => "O tipo especificado � inv�lido, o valor deve ser um inteiro, um float ou uma string num�rica",

    // Zend_Validate_StringLength
    "Invalid type given, value should be a string" => "O tipo especificado � inv�lido, o valor deve ser uma string",
    "'%value%' is less than %min% characters long" => "O tamanho de '%value%' � inferior a %min% caracteres",
    "'%value%' is more than %max% characters long" => "O tamanho de '%value%' � superior a %max% caracteres",
);
