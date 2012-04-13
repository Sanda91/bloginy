<?php
/*
Installing PHPUnit

PHPUnit should be installed using the PEAR Installer. This installer is the 
backbone of PEAR, which provides a distribution system for PHP packages, and is 
shipped with every release of PHP since version 4.3.0.

The PEAR channel (pear.phpunit.de) that is used to distribute PHPUnit needs to 
be registered with the local PEAR environment:

pear channel-discover pear.phpunit.de

This has to be done only once. Now the PEAR Installer can be used to install 
packages from the PHPUnit channel:

pear install phpunit/PHPUnit

After the installation you can find the PHPUnit source files inside your local 
PEAR directory; the path is usually /usr/lib/php/PHPUnit. 
--------------------------------------------------------------------------------

The Command-Line Test Runner

The PHPUnit command-line test runner can be invoked through the phpunit command. 
The following code shows how to run tests with the PHPUnit command-line test 
runner:

phpunit ArabicTest
PHPUnit 3.2.10 by Sebastian Bergmann.

..

Time: 0 seconds


OK (2 tests)

For each test run, the PHPUnit command-line tool prints one character to 
indicate progress:

.   Printed when the test succeeds. 
F   Printed when an assertion fails while running the test method. 

*/

  require_once 'PHPUnit/Framework.php';
  require_once 'Arabic.php';
  
  class ArabicTest extends PHPUnit_Framework_TestCase
  {
      protected $strUTF;
      protected $strWIN;
      protected $strISO;
      protected $arabic;
      
      protected function setUp()
      {
          date_default_timezone_set('UTC');
          
          $this->strUTF = 'خالد الشمعة';
          $this->strWIN = '���� ������';
          $this->strISO = '';
          
          $this->arabic = new Arabic();
          $this->arabic->setInputCharset('windows-1256');
          $this->arabic->setOutputCharset('windows-1256');
      }
      
      protected function tearDown()
      {
          $this->strUTF = null;
          $this->strWIN = null;
          $this->strISO = null;
      }
      
      public function testConvertUtf8ToWin1256()
      {
          $this->arabic->load('ArCharsetC');
          
          $this->arabic->setInputCharset('utf-8');
          $this->arabic->setOutputCharset('windows-1256');
          
          $result = $this->arabic->convert($this->strUTF);
          
          $this->assertEquals($this->strWIN, $result);
      }

      public function testConvertWin1256ToUtf8()
      {
          $this->arabic->load('ArCharsetC');
          
          $this->arabic->setInputCharset('windows-1256');
          $this->arabic->setOutputCharset('utf-8');
          
          $result = $this->arabic->convert($this->strWIN);
          
          $this->assertEquals($this->strUTF, $result);
      }

      public function testDateHegri()
      {
          $this->arabic->load('ArDate');
          
          $time = 176205600;
          $hejri = '����� 26 ��� 1395 10:00:00 ������';
          
          $result = $this->arabic->date('l dS F Y h:i:s A', $time);
          
          $this->assertEquals($hejri, $result);
      }

      public function testDateHegriMinusOneDay()
      {
          $this->arabic->load('ArDate');
          
          $time = 176205600;
          $hejri = 'الجمعة 25 رجب 1395 10:00:00 صباحاً';
          
          $this->arabic->setOutputCharset('utf-8');
          $result = $this->arabic->date('l dS F Y h:i:s A', $time, null, -1);
          
          $this->assertEquals($hejri, $result);
      }
      
      public function testDateArabic()
      {
          $this->arabic->load('ArDate');
          
          $time = 176205600;
          $arabic = '����� 02 ��/����� 1975 10:00:00 ������';
          
          $this->arabic->setMode(4);
          $result = $this->arabic->date('l dS F Y h:i:s A', $time);
          
          $this->assertEquals($arabic, $result);
      }

      public function testDateLibyan()
      {
          $this->arabic->load('ArDate');
          
          $time = 176205600;
          $libyan = '����� 02 ������� 1343 10:00:00 ������';
          
          $this->arabic->setMode(5);
          $result = $this->arabic->date('l dS F Y h:i:s A', $time);
          
          $this->assertEquals($libyan, $result);
      }
      
      public function testEnglishArabicTransliteration()
      {
          $this->arabic->load('ArTransliteration');
          
          $en = 'Formula1';
          $ar = ' �������1';
          
          $result = $this->arabic->en2ar($en);
          
          $this->assertEquals($ar, $result);
      }
      
      public function testArabicEnglishTransliteration()
      {
          $this->arabic->load('EnTransliteration');
          
          $ar = '����� ��������';
          $en = " Khalid Al-Sham'ah";
          
          $result = $this->arabic->ar2en($ar);
          
          $this->assertEquals($en, $result);
      }
      
      public function testArabicGenderMale()
      {
          $this->arabic->load('ArGender');
          
          $name = '���� �����';
          $female = false;
          
          $result = $this->arabic->isFemale($name);
          
          $this->assertEquals($female, $result);
      }
      
      public function testArabicGenderFemale()
      {
          $this->arabic->load('ArGender');
          
          $name = '���� �����';
          $female = true;
          
          $result = $this->arabic->isFemale($name);
          
          $this->assertEquals($female, $result);
      }
      
      public function testSpellNumbersInArabicIdiom1()
      {
          $this->arabic->load('ArNumbers');
          
          $number = 2147483647;
          $spell = '������� � ��� � ��� � ������ ����� � ������� � ���� � ������ ��� � ����� � ��� � ������';
          
          $this->arabic->setFeminine(1);
          $this->arabic->setFormat(1);
          $result = $this->arabic->int2str($number);
          
          $this->assertEquals($spell, $result);
      }
      
      public function testSpellNumbersInArabicIdiom2()
      {
          $this->arabic->load('ArNumbers');
          
          $number = 2147483647;
          $spell = '������� � ��� � ���� � ������ ����� � ������� � ����� � ������ ��� � ����� � ���� � ������';
          
          $this->arabic->setFeminine(2);
          $this->arabic->setFormat(2);
          $result = $this->arabic->int2str($number);
          
          $this->assertEquals($spell, $result);
      }
      
      public function testSpellNumbersInArabicIdiom3()
      {
          $this->arabic->load('ArNumbers');
          
          $number = 2749.317;
          $spell = '����� � ������ � ���� � ������ ����� ������� � ���� ���';
          
          $this->arabic->setFeminine(2);
          $this->arabic->setFormat(2);
          $result = $this->arabic->int2str($number);
          
          $this->assertEquals($spell, $result);
      }
      
      public function testHegriMktime()
      {
          $this->arabic->load('ArMktime');
          
          $time = 1159056000;
          
          $result = $this->arabic->mktime(0, 0, 0, 9, 1, 1427);
          
          $this->assertEquals($time, $result);
      }
      
      public function testHegriMktimePlusOneDay()
      {
          $this->arabic->load('ArMktime');
          
          $time = 1159142400;
          
          $result = $this->arabic->mktime(0, 0, 0, 9, 1, 1427, +1);

          $this->assertEquals($time, $result);
      }
      
      public function testSwapEnglishToArabicKeyboard()
      {
          $this->arabic->load('ArKeySwap');
          
          $english = "Hpf lk hgkhs hglj'vtdkK Hpf hg`dk dldg,k f;gdjil Ygn ,p]hkdm hgHl,v tb drt,k ljv]]dk fdk krdqdk>";
          $arabic = '��� �� ����� ��������� ��� ����� ������ ������� ��� ������� ������ ��� ����� ������� ��� ������.';
          
          $result = $this->arabic->swap_ea($english);
          
          $this->assertEquals($arabic, $result);
      }
      
      public function testSwapArabicToEnglishKeyboard()
      {
          $this->arabic->load('ArKeySwap');
          
          $arabic = "��� ����������� ���� ��� ���� ������ ������� ���� ������� ��� ���� �������� �� ����� � ����� �� ������ ��� � ��� �� ������� �� ���� �� ��� �������� ����������";
          $english = 'Any intelligent fool can make things bigger more complex and more violent. it takes a touch of genius and a lot of courage to move in the opposite direction.';
          
          $result = $this->arabic->swap_ae($arabic);
          
          $this->assertEquals($english, $result);
      }
      
      public function testMaxArabicCharsInA4Line()
      {
          $this->arabic->load('ArGlyphs');
          
          $font_size = 16;
          $max = 101;
          
          $result = $this->arabic->a4_max_chars($font_size);
          
          $this->assertEquals($max, $result);
      }
      
      public function testSplitArabicTextIntoA4Lines()
      {
          $this->arabic->load('ArGlyphs');
          
          $font_size = 16;
          $text = '�� ����� ����ѡ ��� ��� ����� Microsoft ����� ������� �� ����� Ajax ��� ��� ����� ������� ����� ������, �� �� ���� �� ���� ������ ��� ������� �� ����� ����� �������� ������ Outlook ������ ����������. ���� ����� �� ��� ����� Ajax ����� ����� ����� ����ǡ ��� ���� �� ��� (��� ������ ��� ���) ������ �� �������� ��� �� ����� ���� ��� ���� Google �� ��� ������ ���� ������� �������� �� ���ϡ ���� �� ���� ����� �� ��������� ������� ����� ��� ��� ����� �� �� ���� Maps ����� ��� ���� ������ ���������� Gmail ������� ���� ���� ����� ����� �� ���� ����� ������ ����� ��� �� ����� ���� ������� ����� �� �������� ������. ��� ������ �����ɿ ����ǡ ��� �� 13 ���� 2007 ��';
          $lines = 7;
          
          $result = $this->arabic->a4_lines($text, $font_size);
          
          $this->assertEquals($lines, $result);
      }
      
      public function testArabicGlyphsRender()
      {
          $this->arabic->load('ArGlyphs');
          
          $text = '������ ������';
          $glyphs = 'ﻢﻴﺣﺮﻟﺍ ﻦﻤﺣﺮﻟﺍ';
          
          $result = $this->arabic->utf8Glyphs($text);
          
          $this->assertEquals($glyphs, $result);
      }
      
      public function testArabicSoundex()
      {
          $this->arabic->load('ArSoundex');
          
          $name = '�������';
          $soundex = 'K453';
          
          $result = $this->arabic->soundex($name);
          
          $this->assertEquals($soundex, $result);
      }
      
      public function testArabicNounTrue1()
      {
          $this->arabic->load('ArWordTag');
          
          $word = '�������';
          $word_befor = '����';
          $noun = true;
          
          $result = $this->arabic->isNoun($word, $word_befor);
          
          $this->assertEquals($noun, $result);
      }
      
      public function testArabicNounTrue2()
      {
          $this->arabic->load('ArWordTag');
          
          $word = '������';
          $word_befor = '375';
          $noun = true;
          
          $result = $this->arabic->isNoun($word, $word_befor);
          
          $this->assertEquals($noun, $result);
      }
      
      public function testArabicNounTrue3()
      {
          $this->arabic->load('ArWordTag');
          
          $word = '����������';
          $word_befor = '��';
          $noun = true;
          
          $result = $this->arabic->isNoun($word, $word_befor);
          
          $this->assertEquals($noun, $result);
      }
      
      public function testArabicNounTrue4()
      {
          $this->arabic->load('ArWordTag');
          
          $word = '�����';
          $word_befor = '����';
          $noun = true;
          
          $result = $this->arabic->isNoun($word, $word_befor);
          
          $this->assertEquals($noun, $result);
      }
      
      public function testArabicNounFalse1()
      {
          $this->arabic->load('ArWordTag');
          
          $word = '����';
          $word_befor = '��';
          $noun = false;
          
          $result = $this->arabic->isNoun($word, $word_befor);
          
          $this->assertEquals($noun, $result);
      }
      
      public function testArabicNounFalse2()
      {
          $this->arabic->load('ArWordTag');
          
          $word = '������';
          $word_befor = '������';
          $noun = false;
          
          $result = $this->arabic->isNoun($word, $word_befor);
          
          $this->assertEquals($noun, $result);
      }
      
      public function testArabicNounHighlight()
      {
          $this->arabic->load('ArWordTag');
          
          $text = '�� ��� ��� 375 ������ ������ �� ���������� ';
          $tagged = '  ��  ���  ��� 
<span style="background-color: #FFEEAA">  375  ������</span> 
  ������  �� 
<span style="background-color: #FFEEAA">  ����������</span> 
';
          
          $result = $this->arabic->highlightText($text, '#FFEEAA');
          
          $this->assertEquals($tagged, $result);
      }
      
      public function testDetectArabicUtf8Charset()
      {
          $this->arabic->load('ArCharsetD');
          
          $result = $this->arabic->getCharset($this->strUTF);
          
          $this->assertEquals('utf-8', $result);
      }
      
      public function testDetectArabicWin1256Charset()
      {
          $this->arabic->load('ArCharsetD');
          
          $result = $this->arabic->getCharset($this->strWIN);
          
          $this->assertEquals('windows-1256', $result);
      }
      
      public function testArabicStemWhereCondition1()
      {
          $this->arabic->load('ArQuery');
          
          $keyword = '���������';
          $where = "(headline REGEXP '�������(�|(�|�|�|�)�)?')";
          
          $this->arabic->setStrFields('headline');
          $this->arabic->setMode(0);
          
          $result = $this->arabic->getWhereCondition($keyword);
          
          $this->assertEquals($where, $result);
      }
      
      public function testArabicStemWhereCondition2()
      {
          $this->arabic->load('ArQuery');
          
          $keyword = '����� ������';
          $where = "(headline REGEXP '(�|�|�|�)�(�|�|�|�)�(�|(�|�|�|�)�)?') OR (headline REGEXP '�����(�|(�|�|�|�)�)?')";
          
          $this->arabic->setStrFields('headline');
          $this->arabic->setMode(0);
          
          $result = $this->arabic->getWhereCondition($keyword);
          
          $this->assertEquals($where, $result);
      }
      
      public function testArabicStemOrderby1()
      {
          $this->arabic->load('ArQuery');
          
          $keyword = '���������';
          $where = "((CASE WHEN headline REGEXP '�������(�|(�|�|�|�)�)?' THEN 1 ELSE 0 END)) DESC";
          
          $this->arabic->setStrFields('headline');
          $this->arabic->setMode(0);
          
          $result = $this->arabic->getOrderBy($keyword);
          
          $this->assertEquals($where, $result);
      }
      
      public function testArabicStemOrderby2()
      {
          $this->arabic->load('ArQuery');
          
          $keyword = '����� ������';
          $where = "((CASE WHEN headline REGEXP '(�|�|�|�)�(�|�|�|�)�(�|(�|�|�|�)�)?' THEN 1 ELSE 0 END) + (CASE WHEN headline REGEXP '�����(�|(�|�|�|�)�)?' THEN 1 ELSE 0 END)) DESC";
          
          $this->arabic->setStrFields('headline');
          $this->arabic->setMode(0);
          
          $result = $this->arabic->getOrderBy($keyword);
          
          $this->assertEquals($where, $result);
      }
      
      public function testMuslimPrayerTimes()
      {
          $this->arabic->load('Salat');
          
          $Fajr = '4:42';
          $Sunrise = '6:08';
          $Zuhr = '11:57';
          $Asr = '15:14';
          $Maghrib = '17:45';
          $Isha = '19:11';
          
          $this->arabic->setLocation(33.513, 36.292, 2);
          $this->arabic->setDate(7, 3, 2008);
          
          $result = $this->arabic->getPrayTime();
          
          $this->assertEquals($Fajr, $result[0]);
          $this->assertEquals($Sunrise, $result[1]);
          $this->assertEquals($Zuhr, $result[2]);
          $this->assertEquals($Asr, $result[3]);
          $this->assertEquals($Maghrib, $result[4]);
          $this->assertEquals($Isha, $result[5]);
      }
      
      public function testUtf8ArabicTextIdentifier()
      {
          $this->arabic->load('ArIdentifier');
          
          $text = 'Peace &nbsp; سلام &nbsp; &nbsp; Has�t�';
          $pos = array(0 => 13, 1 => 22);
          
          $result = $this->arabic->identify($text);
          
          $this->assertEquals($pos[0], $result[0]);
          $this->assertEquals($pos[1], $result[1]);
      }

      public function testStrToTime1()
      {
          $this->arabic->load('ArStrToTime');
          
          $time = time();
          $this->arabic->setInputCharset('utf-8');

          $ar_text = 'الخميس القادم';
          $en_text = 'Next Thursday';
          
          $ar_result = $this->arabic->strtotime($ar_text, $time);
          $en_result = strtotime($en_text, $time);

          $this->assertEquals($ar_result, $en_result);
      }

      public function testStrToTime2()
      {
          $this->arabic->load('ArStrToTime');
          
          $time = time();
          $this->arabic->setInputCharset('utf-8');

          $ar_text = 'قبل تسعة أيام';
          $en_text = '-9 Days';
          
          $ar_result = $this->arabic->strtotime($ar_text, $time);
          $en_result = strtotime($en_text, $time);

          $this->assertEquals($ar_result, $en_result);
      }

      public function testStrToTime3()
      {
          $this->arabic->load('ArStrToTime');
          
          $time = time();
          $this->arabic->setInputCharset('utf-8');

          $ar_text = 'بعد أسبوع وثلاثة أيام';
          $en_text = '+1 Week +3 Days';
          
          $ar_result = $this->arabic->strtotime($ar_text, $time);
          $en_result = strtotime($en_text, $time);

          $this->assertEquals($ar_result, $en_result);
      }

      public function testStrToTime4()
      {
          $this->arabic->load('ArStrToTime');
          
          $time = time();

          $hijri = '1 ����� 1429';
          $unix_time_stamp  = 1220313600;
          
          $result = $this->arabic->strtotime($hijri, $time);

          $this->assertEquals($unix_time_stamp, $result);
      }
      
      public function testWindows1256ToHtml()
      {
          $this->arabic->load('ArCharsetC');
          
          $text = '���� ������';
          $html = '&#1582;&#1575;&#1604;&#1583; &#1575;&#1604;&#1588;&#1605;&#1593;&#1577;';
          
          $result = $this->arabic->win2html($text);
          
          $this->assertEquals($html, $result);
      }
      
      public function testAllWordForms()
      {
          $this->arabic->load('ArQuery');
          
          $word  = '����������';
          $forms = '���������� �������� ������ ������� ��������� �������� �������� �������� �������� ���������� �������� ������ ������� ��������� �������� �������� �������� ��������';
          
          $result = $this->arabic->allForms($word);
          
          $this->assertEquals($forms, $result);
      }
      
      public function testIsArabicTrue()
      {
          $this->arabic->load('ArIdentifier');
          
          $arabic = '���� ������';
          $is_it  = true;
          
          $result = $this->arabic->isArabic($arabic, 'windows-1256');
          
          $this->assertEquals($is_it, $result);
      }
      
      public function testIsArabicFalse()
      {
          $this->arabic->load('ArIdentifier');
          
          $arabic = 'Khaled Al-Shamaa';
          $is_it  = false;
          
          $result = $this->arabic->isArabic($arabic);
          
          $this->assertEquals($is_it, $result);
      }
      
      public function testArabicStem1()
      {
          $this->arabic->load('ArStemmer');
          
          $word = '��������';
          $stem = '����';
          
          $result = $this->arabic->stem($word);
          
          $this->assertEquals($stem, $result);
      }
      
      public function testArabicStem2()
      {
          $this->arabic->load('ArStemmer');
          
          $word = '����������';
          $stem = '�����';
          
          $result = $this->arabic->stem($word);
          
          $this->assertEquals($stem, $result);
      }
      
      public function testArabicStem3()
      {
          $this->arabic->load('ArStemmer');
          
          $word = '��������';
          $stem = '���';
          
          $result = $this->arabic->stem($word);
          
          $this->assertEquals($stem, $result);
      }
      
      public function testArabicStem4()
      {
          $this->arabic->load('ArStemmer');
          
          $word = '��������';
          $stem = '����';
          
          $result = $this->arabic->stem($word);
          
          $this->assertEquals($stem, $result);
      }
      
      public function testArabicStandard()
      {
          $this->arabic->load('ArStandard');
          
          $sentence = '��� �� ���� � � ��� ������ ����� ����� ��� ��� � ������ !� ���� ����( ��� ����� )�� ��� �����"������� ������ "��- ������ ������ -���...... ';
          $standard = '��� �� ���� ���� ������ ����� ����� ��� ��� �������! ����� ���� (��� �����) �� ��� ����� "������� ������" �� -������ ������- ���...';
          
          $result = $this->arabic->standard($sentence);
          
          $this->assertEquals($standard, $result);
      }
  }
?>