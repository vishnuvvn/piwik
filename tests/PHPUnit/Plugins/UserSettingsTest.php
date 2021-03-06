<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */
require_once 'UserSettings/UserSettings.php';
require_once 'UserSettings/functions.php';

class UserSettingsTest extends PHPUnit_Framework_TestCase
{
    // User agent strings and expected parser results
    public function getUserAgents()
    {
        return array(
            // array('User Agent String', array(
            //     array( browser_id, name, short_name, version, major_number, minor_number, family ),
            //     array( os_id, name, short_name ))),

            // Special: URL encoded IE8
            array('Mozilla/4.0+(compatible;+MSIE+8.0;+Windows+NT+6.1;+WOW64;+Trident/4.0;+GTB7.4;+SLCC2;+.NET+CLR+2.0.50727;+.NET+CLR+3.5.30729;+.NET+CLR+3.0.30729;+Media+Center+PC+6.0;+.NET4.0C;+.NET4.0E;+MS-RTC+LM+8;+InfoPath.2)', array(
                array('IE', 'Internet Explorer', 'IE', '8.0', '8', '0', 'ie'),
                array('WI7', 'Windows 7', 'Win 7'))),

            // ABrowse
            array('Mozilla/5.0 (compatible; U; ABrowse 0.6; Syllable) AppleWebKit/420+ (KHTML, like Gecko)', array(
                array('AB', 'ABrowse', 'ABrowse', '0.6', '0', '6', 'webkit'),
                array('SYL', 'Syllable', 'Syllable'))),
            array('Mozilla/5.0 (compatible; ABrowse 0.4; Syllable)', array(
                array('AB', 'ABrowse', 'ABrowse', '0.4', '0', '4', 'webkit'),
                array('SYL', 'Syllable', 'Syllable'))),

            // Acoo Browser (treat as IE)
            array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB6; Acoo Browser; .NET CLR 1.1.4322; .NET CLR 2.0.50727)', array(
                array('IE', 'Internet Explorer', 'IE', '8.0', '8', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Acoo Browser; .NET CLR 2.0.50727; .NET CLR 1.1.4322)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; Acoo Browser; .NET CLR 1.1.4322; .NET CLR 2.0.50727)', array(
                array('IE', 'Internet Explorer', 'IE', '6.0', '6', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // Amaya
            array('amaya/9.52 libwww/5.4.0', array(
                array('AM', 'Amaya', 'Amaya', '9.52', '9', '52', 'unknown'),
                false)),

            // AmigaVoyager
            array('AmigaVoyager/3.2 (AmigaOS/MC680x0)', array(
                array('AV', 'AmigaVoyager', 'AmigaVoyager', '3.2', '3', '2', 'unknown'),
                array('AMI', 'AmigaOS', 'AmigaOS'))),
            array('AmigaVoyager/2.95 (compatible; MC680x0; AmigaOS; SV1)', array(
                array('AV', 'AmigaVoyager', 'AmigaVoyager', '2.95', '2', '95', 'unknown'),
                array('AMI', 'AmigaOS', 'AmigaOS'))),
            array('AmigaVoyager/2.95 (compatible; MC680x0; AmigaOS)', array(
                array('AV', 'AmigaVoyager', 'AmigaVoyager', '2.95', '2', '95', 'unknown'),
                array('AMI', 'AmigaOS', 'AmigaOS'))),

            // Android
            array('Mozilla/5.0 (Linux; U; Android 1.1; en-us; dream) AppleWebKit/525.10+ (KHTML, like Gecko) Version/3.0.4 Mobile Safari/523.12.2', array(
                array('SF', 'Safari', 'Safari', '3.0', '3', '0', 'webkit'),
                array('AND', 'Android', 'Android'))),
            array('Mozilla/5.0 (Linux; U; Android 2.2.1; en-us; Nexus One Build/FRG83) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', array(
                array('SF', 'Safari', 'Safari', '4.0', '4', '0', 'webkit'),
                array('AND', 'Android', 'Android'))),
            array('Mozilla/5.0 (Linux; U; Android 2.2.1; en-us; device Build/FRG83) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Safari/533.1', array(
                array('SF', 'Safari', 'Safari', '4.0', '4', '0', 'webkit'),
                array('AND', 'Android', 'Android'))),
            array('Mozilla/5.0 (Linux; U; Android 4.0.1; en-us; Galaxy Nexus Build/ITL41D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30', array(
                array('SF', 'Safari', 'Safari', '4.0', '4', '0', 'webkit'),
                array('AND', 'Android', 'Android'))),

            // Android - Mobile Chrome
            /*
                    array('rray('Mozilla/5.0 (Linux; U; Android 4.1; en-us) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.16 Safari/534.24', array(
                        array('CH', 'Chrome', 'Chrome', '11.0', '11', '0', 'webkit'),
                        array('AND', 'Android', 'Android'))),
            */
            array('Mozilla/5.0 (Linux; U; Android 4.0.1; en-us; Galaxy Nexus Build/ITL41F) AppleWebKit/535.7 (KHTML, like Gecko) CrMo/16.0.912.75 Mobile Safari/535.7', array(
                array('CH', 'Chrome', 'Chrome', '16.0', '16', '0', 'webkit'),
                array('AND', 'Android', 'Android'))),

            // AOL / America Online Browser (treat as IE)
            array('Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.5; AOLBuild 4337.43; Windows NT 5.1; .NET CLR 1.1.4322)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.1; AOLBuild 4334.5009; Windows NT 5.1; GTB5; .NET CLR 1.1.4322)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.0; Windows NT 5.1; InfoPath.1)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 6.0; AOL 8.0; Windows NT 5.1; SV1)', array(
                array('IE', 'Internet Explorer', 'IE', '6.0', '6', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; AOL 7.0; Windows NT 5.1)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 5.5; AOL 6.0; Windows 98; Win 9x 4.90)', array(
                array('IE', 'Internet Explorer', 'IE', '5.5', '5', '5', 'ie'),
                array('WME', 'Windows Me', 'Win Me'))),
            array('Mozilla/4.0 (compatible; MSIE 5.5; AOL 5.0; Windows NT 5.0)', array(
                array('IE', 'Internet Explorer', 'IE', '5.5', '5', '5', 'ie'),
                array('W2K', 'Windows 2000', 'Win 2000'))),
            array('Mozilla/4.0 (compatible; MSIE 4.01; AOL 4.0; Windows 95)', array(
                array('IE', 'Internet Explorer', 'IE', '4.01', '4', '01', 'ie'),
                array('W95', 'Windows 95', 'Win 95'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; America Online Browser 1.1; Windows NT 5.1; (R1 1.5); .NET CLR 2.0.50727; InfoPath.1)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 6.0; America Online Browser 1.1; Windows 98)', array(
                array('IE', 'Internet Explorer', 'IE', '6.0', '6', '0', 'ie'),
                array('W98', 'Windows 98', 'Win 98'))),

            // Arora
            array('Mozilla/5.0 (X11; U; Linux; de-DE) AppleWebKit/527+ (KHTML, like Gecko, Safari/419.3) Arora/0.8.0', array(
                array('AR', 'Arora', 'Arora', '0.8', '0', '8', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/527+ (KHTML, like Gecko, Safari/419.3) Arora/0.6', array(
                array('AR', 'Arora', 'Arora', '0.6', '0', '6', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.2; pt-BR) AppleWebKit/527+ (KHTML, like Gecko, Safari/419.3) Arora/0.4 (Change: )', array(
                array('AR', 'Arora', 'Arora', '0.4', '0', '4', 'webkit'),
                array('WS3', 'Windows Server 2003 / XP x64', 'Win S2003'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.3 (Change: 287 c9dfb30)', array(
                array('AR', 'Arora', 'Arora', '0.3', '0', '3', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.2 (Change: 189 35c14e0)', array(
                array('AR', 'Arora', 'Arora', '0.2', '0', '2', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),

            // Avant (treat as IE)
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser; .NET CLR 2.0.50727; MAXTHON 2.0)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; Avant Browser; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0)', array(
                array('IE', 'Internet Explorer', 'IE', '8.0', '8', '0', 'ie'),
                array('WI7', 'Windows 7', 'Win 7'))),

            // AWeb
            array('Amiga-AWeb/3.5.07 beta', array(
                array('AW', 'Amiga AWeb', 'AWeb', '3.5', '3', '5', 'unknown'),
                array('AMI', 'AmigaOS', 'AmigaOS'))),
            array('Mozilla/6.0; (Spoofed by Amiga-AWeb/3.5.07 beta)', array(
                array('AW', 'Amiga AWeb', 'AWeb', '3.5', '3', '5', 'unknown'),
                array('AMI', 'AmigaOS', 'AmigaOS'))),
            array('MSIE/6.0; (Spoofed by Amiga-AWeb/3.4APL)', array(
                array('AW', 'Amiga AWeb', 'AWeb', '3.4', '3', '4', 'unknown'),
                array('AMI', 'AmigaOS', 'AmigaOS'))),

            // Beonex
            array('Mozilla/5.0 (Windows; U; WinNT; en; rv:1.0.2) Gecko/20030311 Beonex/0.8.2-stable', array(
                array('BE', 'Beonex', 'Beonex', '0.8', '0', '8', 'unknown'),
                array('WNT', 'Windows NT', 'Win NT'))),
            array('Mozilla/5.0 (Windows; U; WinNT; en; Preview) Gecko/20020603 Beonex/0.8-stable', array(
                array('BE', 'Beonex', 'Beonex', '0.8', '0', '8', 'unknown'),
                array('WNT', 'Windows NT', 'Win NT'))),

            // BlackBerry
            array('BlackBerry8700/4.1.0 Profile/MIDP-2.0 Configuration/CLDC-1.1', array(
                array('BB', 'BlackBerry', 'BlackBerry', '4.1', '4', '1', 'webkit'),
                array('BLB', 'BlackBerry', 'BlackBerry'))),

            array('Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en) AppleWebKit/534.1+ (KHTML, Like Gecko) Version/6.0.0.141 Mobile Safari/534.1+', array(
                array('BB', 'BlackBerry', 'BlackBerry', '6.0', '6', '0', 'webkit'),
                array('BLB', 'BlackBerry', 'BlackBerry'))),

            array('Mozilla/5.0 (PlayBook; U; RIM Tablet OS 1.0.0; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/0.0.1 Safari/534.11+', array(
                array('BP', 'PlayBook', 'PlayBook', '0.0', '0', '0', 'webkit'),
                array('QNX', 'QNX', 'QNX'))),

            // BrowseX
            array('Mozilla/4.61 [en] (X11; U; ) - BrowseX (2.0.0 Windows)', array(
                array('BX', 'BrowseX', 'BrowseX', '2.0', '2', '0', 'unknown'),
                false)),

            // Camino (formerly known as Chimera; not to be confused with another browser also named Chimera)
            array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en; rv:1.9.0.8pre) Gecko/2009022800 Camino/2.0b3pre', array(
                array('CA', 'Camino', 'Camino', '2.0', '2', '0', 'gecko'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en; rv:1.9.0.10pre) Gecko/2009041800 Camino/2.0b3pre (like Firefox/3.0.10pre)', array(
                array('CA', 'Camino', 'Camino', '2.0', '2', '0', 'gecko'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en; rv:1.8.1.4pre) Gecko/20070511 Camino/1.6pre', array(
                array('CA', 'Camino', 'Camino', '1.6', '1', '6', 'gecko'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en; rv:1.8.1.6) Gecko/20070809 Firefox/2.0.0.6 Camino/1.5.1', array(
                array('CA', 'Camino', 'Camino', '1.5', '1', '5', 'gecko'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.0.1) Gecko/20030306 Camino/0.7', array(
                array('CA', 'Camino', 'Camino', '0.7', '0', '7', 'gecko'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; pl-PL; rv:1.0.1) Gecko/20021111 Chimera/0.6', array(
                array('CA', 'Camino', 'Camino', '0.6', '0', '6', 'gecko'),
                array('MAC', 'Mac OS', 'Mac OS'))),

            // Cheshire
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/418.8 (KHTML, like Gecko, Safari) Cheshire/1.0.UNOFFICIAL', array(
                array('CS', 'Cheshire', 'Cheshire', '1.0', '1', '0', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/419 (KHTML, like Gecko, Safari/419.3) Cheshire/1.0.ALPHA', array(
                array('CS', 'Cheshire', 'Cheshire', '1.0', '1', '0', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/418.9 (KHTML, like Safari) Cheshire/1.0.ALPHA', array(
                array('CS', 'Cheshire', 'Cheshire', '1.0', '1', '0', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),

            // Chrome / Chromium
            array('Mozilla/5.0 (Windows; U; Windows NT 5.2; en-US) AppleWebKit/532.9 (KHTML, like Gecko) Chrome/5.0.310.0 Safari/532.9', array(
                array('CH', 'Chrome', 'Chrome', '5.0', '5', '0', 'webkit'),
                array('WS3', 'Windows Server 2003 / XP x64', 'Win S2003'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Chrome/1.0.154.48 Safari/525.19', array(
                array('CH', 'Chrome', 'Chrome', '1.0', '1', '0', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (Linux; U; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.2.149.27 Safari/525.13', array(
                array('CH', 'Chrome', 'Chrome', '0.2', '0', '2', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (Windows NT 6.0; WOW64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.16 Safari/534.24', array(
                array('CH', 'Chrome', 'Chrome', '11.0', '11', '0', 'webkit'),
                array('WVI', 'Windows Vista', 'Win Vista'))),
            array('Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/534.24 (KHTML, like Gecko) Chrome/11.0.696.16 Safari/534.24', array(
                array('CH', 'Chrome', 'Chrome', '11.0', '11', '0', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),

            // Chrome Frame
            array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; chromeframe/11.0.660.0)', array(
                array('CF', 'Chrome Frame', 'Chrome Frame', '11.0', '11', '0', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) chromeframe/11.0.660.0', array(
                array('CF', 'Chrome Frame', 'Chrome Frame', '11.0', '11', '0', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; chromeframe/11.0.660.0) AppleWebKit/534.18 (KHTML, like Gecko) Chrome/11.0.660.0 Safari/534.18', array(
                array('CF', 'Chrome Frame', 'Chrome Frame', '11.0', '11', '0', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/534.18 (KHTML, like Gecko) Chrome/11.0.660.0 Safari/534.18', array(
                array('CH', 'Chrome', 'Chrome', '11.0', '11', '0', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // ChromePlus (treat as Chrome)
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/532.2 (KHTML, like Gecko) ChromePlus/4.0.222.3 Chrome/4.0.222.3 Safari/532.2', array(
                array('CH', 'Chrome', 'Chrome', '4.0', '4', '0', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.28.3 (KHTML, like Gecko) Version/3.2.3 ChromePlus/4.0.222.3 Chrome/4.0.222.3 Safari/525.28.3', array(
                array('CH', 'Chrome', 'Chrome', '3.2', '3', '2', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // CometBird
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.0.5) Gecko/2009011615 Firefox/3.0.5 CometBird/3.0.5', array(
                array('CO', 'CometBird', 'CometBird', '3.0', '3', '0', 'gecko'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // Crazy Browser (treat as IE)
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; InfoPath.2; .NET CLR 2.0.50727; .NET CLR 1.1.4322; Crazy Browser 3.0.0 Beta2)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Avant Browser; .NET CLR 2.0.50727; .NET CLR 3.0.04506.590; .NET CLR 3.5.20706; Crazy Browser 2.0.1)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // Dillo
            array('Dillo/2.0', array(
                array('DI', 'Dillo', 'Dillo', '2.0', '2', '0', 'unknown'),
                false)),
            array('Dillo/0.6.4', array(
                array('DI', 'Dillo', 'Dillo', '0.6', '0', '6', 'unknown'),
                false)),

            // Dolfin (or Dolphin, depending on which Samsung documentation you read); and yes, it's "bada" (lower-case)
            array('Mozilla/5.0 (SAMSUNG; SAMSUNG-GT-S5250/S5250AIJI3; U; Bada/1.0; it-it) AppleWebKit/533.1 (KHTML, like Gecko) Dolfin/2.0 Mobile WQVGA SMM-MMS/1.2.0 NexPlayer/3.0 profile/MIDP-2.1 configuration/CLDC-1.1 OPN-B', array(
                array('DF', 'Dolfin', 'Dolfin', '2.0', '2', '0', 'webkit'),
                array('SBA', 'bada', 'bada'))),
            array('Mozilla/5.0 (SAMSUNG; SAMSUNG-GT-S8530/S8530XXJKA; U; Bada/1.2; en-us) AppleWebKit/533.1 (KHTML, like Gecko) Dolfin/2.2 Mobile WVGA SMM-MMS/1.2.0 OPN-B', array(
                array('DF', 'Dolfin', 'Dolfin', '2.2', '2', '2', 'webkit'),
                array('SBA', 'bada', 'bada'))),

            // ELinks
            array('ELinks/0.12~pre2.dfsg0-1ubuntu1-lite (textmode; Debian; Linux 2.6.32-4-jolicloud i686; 143x37-2)', array(
                array('EL', 'ELinks', 'ELinks', '0.12', '0', '12', 'unknown'),
                array('LIN', 'Linux', 'Linux'))),
            array('ELinks/0.12pre5.GIT (textmode; CYGWIN_NT-6.1 1.7.1(0.218/5/3) i686; 80x24-2)', array(
                array('EL', 'ELinks', 'ELinks', '0.12', '0', '12', 'unknown'),
                array('WI7', 'Windows 7', 'Win 7'))),
            array('ELinks (0.4.3; NetBSD 3.0.2_PATCH sparc64; 141x19)', array(
                array('EL', 'ELinks', 'ELinks', '0.4', '0', '4', 'unknown'),
                array('NBS', 'NetBSD', 'NetBSD'))),

            // Epiphany
            array('Mozilla/5.0 (X11; U; Linux i686; en-us) AppleWebKit/531.2+ (KHTML, like Gecko) Safari/531.2+ Epiphany/2.29.5', array(
                array('EP', 'Epiphany', 'Epiphany', '2.29', '2', '29', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en; rv:1.9.0.11) Gecko/20080528 Epiphany/2.22 Firefox/3.0', array(
                // technically, this should be 'gecko' but UserAgentParser only supports one browserType (family) per browser
                array('EP', 'Epiphany', 'Epiphany', '2.22', '2', '22', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),

            // Fennec
            array('Mozilla/5.0 (Android; Linux armv7l; rv:2.0.1) Gecko/20100101 Firefox/4.0.1 Fennec/2.0.1', array(
                array('FE', 'Fennec', 'Fennec', '2.0', '2', '0', 'gecko'),
                array('AND', 'Android', 'Android'))),
            array('Mozilla/5.0 (Maemo; Linux armv7l; rv2.0.1) Gecko/20100101 Firefox/4.0.1 Fennec/2.0.1', array(
                array('FE', 'Fennec', 'Fennec', '2.0', '2', '0', 'gecko'),
                array('MAE', 'Maemo', 'Maemo'))),
            array('Mozilla/5.0 (X11; Linux i686; rv2.0.1) Gecko/20100101 Firefox/4.0.1 Fennec/2.0.1', array(
                array('FE', 'Fennec', 'Fennec', '2.0', '2', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10.5; en-US; rv:1.9.2a1pre) Gecko/20090626 Fennec/1.0b2', array(
                array('FE', 'Fennec', 'Fennec', '1.0', '1', '0', 'gecko'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (X11; U; Linux armv6l; en-US; rv:1.9.1b1pre) Gecko/20081005220218 Gecko/2008052201 Fennec/0.9pre', array(
                array('FE', 'Fennec', 'Fennec', '0.9', '0', '9', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Linux armv6l; en-US; rv:1.9.1a1pre) Gecko/2008071707 Fennec/0.5', array(
                array('FE', 'Fennec', 'Fennec', '0.5', '0', '5', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),

            // Firefox (formerly Firebird, formerly Phoenix; and rebranded versions)
            array('Mozilla/5.0 (X11; Linux i686; rv:5.0a2) Gecko/20110413 Firefox/5.0a2', array(
                array('FF', 'Firefox', 'Firefox', '5.0', '5', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1', array(
                array('FF', 'Firefox', 'Firefox', '4.0', '4', '0', 'gecko'),
                array('WI7', 'Windows 7', 'Win 7'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:2.0a1pre) Gecko/2008060602 Minefield/4.0a1pre', array(
                array('FF', 'Firefox', 'Firefox', '4.0', '4', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:2.0a1pre) Gecko/2008060602 Minefield/4.0a1p', array(
                array('FF', 'Firefox', 'Firefox', '4.0', '4', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.2a2pre) Gecko/20090826 Namoroka/3.6a2pre', array(
                array('FF', 'Firefox', 'Firefox', '3.6', '3', '6', 'gecko'),
                array('WVI', 'Windows Vista', 'Win Vista'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2) Gecko/20100119 Namoroka/3.6', array(
                array('FF', 'Firefox', 'Firefox', '3.6', '3', '6', 'gecko'),
                array('WI7', 'Windows 7', 'Win 7'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1b4pre) Gecko/20090420 Shiretoko/3.5b4pre (.NET CLR 3.5.30729)', array(
                array('FF', 'Firefox', 'Firefox', '3.5', '3', '5', 'gecko'),
                array('WI7', 'Windows 7', 'Win 7'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1.2) Gecko/20090803 Ubuntu/9.04 (jaunty) Shiretoko/3.5.2', array(
                array('FF', 'Firefox', 'Firefox', '3.5', '3', '5', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-GB; rv:1.9.0.6) Gecko/2009011913 Firefox/3.0.6', array(
                array('FF', 'Firefox', 'Firefox', '3.0', '3', '0', 'gecko'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9a7) Gecko/2007080210 GranParadiso/3.0a7', array(
                array('FF', 'Firefox', 'Firefox', '3.0', '3', '0', 'gecko'),
                array('WVI', 'Windows Vista', 'Win Vista'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.1) Gecko/2008072716 IceCat/3.0.1-g1', array(
                array('FF', 'Firefox', 'Firefox', '3.0', '3', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Linux x86_64; en-US; rv:1.9.0.1) Gecko/2008071420 Iceweasel/3.0.1 (Debian-3.0.1-1)', array(
                array('FF', 'Firefox', 'Firefox', '3.0', '3', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1b2) Gecko/20060821 BonEcho/2.0b2', array(
                array('FF', 'Firefox', 'Firefox', '2.0', '2', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Darwin Power Macintosh; en-US; rv:1.8.0.12) Gecko/20070803 Firefox/1.5.0.12 Fink Community Edition', array(
                array('FF', 'Firefox', 'Firefox', '1.5', '1', '5', 'gecko'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.6b) Gecko/20031212 Firebird/0.7+', array(
                array('FB', 'Firebird', 'Firebird', '0.7', '0', '7', 'gecko'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (Windows; U; Win98; de-DE; rv:1.4b) Gecko/20030516 Mozilla Firebird/0.6', array(
                array('FB', 'Firebird', 'Firebird', '0.6', '0', '6', 'gecko'),
                array('W98', 'Windows 98', 'Win 98'))),
            array('Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.3a) Gecko/20021207 Phoenix/0.5', array(
                array('PX', 'Phoenix', 'Phoenix', '0.5', '0', '5', 'gecko'),
                array('WNT', 'Windows NT', 'Win NT'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.2b) Gecko/20020923 Phoenix/0.1', array(
                array('PX', 'Phoenix', 'Phoenix', '0.1', '0', '1', 'gecko'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (Nintendo DS v4; U; M3 Adapter CF + PassMe2; en-US; rv:1.8.0.6 ) Gecko/20060728 Firefox/1.5.0.6 (firefox.gba.ds)', array(
                array('FF', 'Firefox', 'Firefox', '1.5', '1', '5', 'gecko'),
                array('NDS', 'Nintendo DS', 'DS'))),
            array('Mozilla/5.0 (Android; Mobile; rv:15.0) Gecko/15.0 Firefox/15.0a1', array(
                array('FF', 'Firefox', 'Firefox', '15.0', '15', '0', 'gecko'),
                array('AND', 'Android', 'Android'))),

            // Flock
            array('Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/532.5 (KHTML, like Gecko) Flock/3.0.0.3737 Chrome/4.1.249.1071 Safari/532.5', array(
                array('FL', 'Flock', 'Flock', '3.0', '3', '0', 'webkit'),
                array('WI7', 'Windows 7', 'Win 7'))),
            // pre-3.0 is actually gecko
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.7) Gecko/20091221 Firefox/3.5.7 Flock/2.5.6 (.NET CLR 3.5.30729)', array(
                array('FL', 'Flock', 'Flock', '2.5', '2', '5', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.7) Gecko/20091221 AppleWebKit/531.21.8 KHTML/4.3.5 (like Gecko) Firefox/3.5.7 Flock/2.5.6 (.NET CLR 3.5.30729)', array(
                array('FL', 'Flock', 'Flock', '2.5', '2', '5', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.7) Gecko/20091221 AppleWebKit/531.21.8 (KHTML, like Gecko) Firefox/3.5.7 Flock/2.5.6 (.NET CLR 3.5.30729)', array(
                array('FL', 'Flock', 'Flock', '2.5', '2', '5', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US; rv:1.8.1.9) Gecko/20071106 Firefox/2.0.0.9 Flock/1.0.1', array(
                array('FL', 'Flock', 'Flock', '1.0', '1', '0', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8.1.8) Gecko/20071101 Firefox/2.0.0.8 Flock/1.0', array(
                array('FL', 'Flock', 'Flock', '1.0', '1', '0', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X Mach-O; en-US; rv:1.8b5) Gecko/20051021 Flock/0.4 Firefox/1.0+', array(
                array('FL', 'Flock', 'Flock', '0.4', '0', '4', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),

            // Fluid
            array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_1; nl-nl) AppleWebKit/532.3+ (KHTML, like Gecko) Fluid/0.9.6 Safari/532.3+', array(
                array('FD', 'Fluid', 'Fluid', '0.9', '0', '9', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Fluid/0.9.4 Safari/525.13', array(
                array('FD', 'Fluid', 'Fluid', '0.9', '0', '9', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // Galeon
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.8) Gecko/20090327 Galeon/2.0.7', array(
                array('GA', 'Galeon', 'Galeon', '2.0', '2', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; OpenBSD i386; en-US; rv:1.8.1.19) Gecko/20090701 Galeon/2.0.7', array(
                array('GA', 'Galeon', 'Galeon', '2.0', '2', '0', 'gecko'),
                array('OBS', 'OpenBSD', 'OpenBSD'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.19) Gecko/20081216 Galeon/2.0.4 Firefox/2.0.0.19', array(
                array('GA', 'Galeon', 'Galeon', '2.0', '2', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.13pre) Gecko/20080207 Galeon/2.0.1 (Ubuntu package 2.0.1-1ubuntu2) Firefox/1.5.0.13pre', array(
                array('GA', 'Galeon', 'Galeon', '2.0', '2', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; FreeBSD i386; en-US; rv:1.6) Gecko/20040406 Galeon/1.3.15', array(
                array('GA', 'Galeon', 'Galeon', '1.3', '1', '3', 'gecko'),
                array('BSD', 'FreeBSD', 'FreeBSD'))),
            array('Mozilla/5.0 Galeon/1.2.9 (X11; Linux i686; U;) Gecko/20021213 Debian/1.2.9-0.bunk', array(
                array('GA', 'Galeon', 'Galeon', '1.2', '1', '2', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 Galeon/1.0.3 (X11; Linux i686; U;) Gecko/0', array(
                array('GA', 'Galeon', 'Galeon', '1.0', '1', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),

            // Google Earth embedded browser
            array('Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/532.4 (KHTML, like Gecko) Google Earth/5.2.1.1329 Safari/532.4', array(
                array('GE', 'Google Earth', 'Google Earth', '5.2', '5', '2', 'webkit'),
                array('WI7', 'Windows 7', 'Win 7'))),

            // GreenBrowser (treat as IE)
            array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.0.04506; Media Center PC 5.0; .NET CLR 3.5.21022; GreenBrowser)', array(
                array('IE', 'Internet Explorer', 'IE', '6.0', '6', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Trident/4.0; SLCC1; .NET CLR 2.0.50727; .NET CLR 3.5.30729; InfoPath.2; .NET CLR 3.0.30729; GreenBrowser)', array(
                array('IE', 'Internet Explorer', 'IE', '8.0', '8', '0', 'ie'),
                array('WVI', 'Windows Vista', 'Win Vista'))),

            // Hana
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/418.9 (KHTML, like Gecko) Hana/1.1', array(
                array('HA', 'Hana', 'Hana', '1.1', '1', '1', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/417.9 (KHTML, like Gecko) Hana/1.0', array(
                array('HA', 'Hana', 'Hana', '1.0', '1', '0', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),

            // HotJava
            array('HotJava/1.1.2 FCS', array(
                array('HJ', 'HotJava', 'HotJava', '1.1', '1', '1', 'unknown'),
                false)),
            array('HotJava/1.0.1/JRE1.1.x', array(
                array('HJ', 'HotJava', 'HotJava', '1.0', '1', '0', 'unknown'),
                false)),

            // iBrowse
            array('Mozilla/5.0 (compatible; IBrowse 3.0; AmigaOS4.0)', array(
                array('IB', 'IBrowse', 'IBrowse', '3.0', '3', '0', 'unknown'),
                array('AMI', 'AmigaOS', 'AmigaOS'))),
            array('Mozilla/4.0 (compatible; IBrowse 2.3; AmigaOS4.0)', array(
                array('IB', 'IBrowse', 'IBrowse', '2.3', '2', '3', 'unknown'),
                array('AMI', 'AmigaOS', 'AmigaOS'))),
            array('IBrowse/2.4 (AmigaOS 3.9; 68K)', array(
                array('IB', 'IBrowse', 'IBrowse', '2.4', '2', '4', 'unknown'),
                array('AMI', 'AmigaOS', 'AmigaOS'))),
            array('IBrowse/2.3 (AmigaOS V51)', array(
                array('IB', 'IBrowse', 'IBrowse', '2.3', '2', '3', 'unknown'),
                array('AMI', 'AmigaOS', 'AmigaOS'))),
            array('IBrowse/2.3 (AmigaOS 4.0)', array(
                array('IB', 'IBrowse', 'IBrowse', '2.3', '2', '3', 'unknown'),
                array('AMI', 'AmigaOS', 'AmigaOS'))),

            // iCab
            array('iCab/4.5 (Macintosh; U; PPC Mac OS X)', array(
                array('IC', 'iCab', 'iCab', '4.5', '4', '5', 'unknown'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('iCab/4.5 (Macintosh; U; Mac OS X Leopard 10.5.7)', array(
                array('IC', 'iCab', 'iCab', '4.5', '4', '5', 'unknown'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (compatible; iCab 3.0.5; Macintosh; U; PPC Mac OS)', array(
                array('IC', 'iCab', 'iCab', '3.0', '3', '0', 'unknown'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (compatible; iCab 3.0.5; Macintosh; U; PPC Mac OS X)', array(
                array('IC', 'iCab', 'iCab', '3.0', '3', '0', 'unknown'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS; en) iCab 3', array(
                array('IC', 'iCab', 'iCab', '3.0', '3', '0', 'unknown'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/4.5 (compatible; iCab 2.7.1; Macintosh; I; PPC)', array(
                array('IC', 'iCab', 'iCab', '2.7', '2', '7', 'unknown'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('iCab/2.9.8 (Macintosh; U; 68K)', array(
                array('IC', 'iCab', 'iCab', '2.9', '2', '9', 'unknown'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Lynx/2.8 (compatible; iCab 2.9.8; Macintosh; U; 68K)', array(
                array('IC', 'iCab', 'iCab', '2.9', '2', '9', 'unknown'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/4/5 (compatible; iCab 2.9.8; Macintosh; U; 68K)', array(
                array('IC', 'iCab', 'iCab', '2.9', '2', '9', 'unknown'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/4.0 (compatible; MSIE 5.0; Mac_PowerPC)', array(
                array('IE', 'Internet Explorer', 'IE', '5.0', '5', '0', 'ie'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/4.76 (Macintosh; I; PPC)', array(
                array('NS', 'Netscape', 'Netscape', '4.76', '4', '76', 'gecko'),
                array('MAC', 'Mac OS', 'Mac OS'))),

            // Internet Explorer
            array('Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0; Xbox)', array(
                array('IE', 'Internet Explorer', 'IE', '9.0', '9', '0', 'ie'),
                array('XBX', 'Xbox', 'Xbox'))),
            array('Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; Xbox)', array(
                array('IE', 'Internet Explorer', 'IE', '9.0', '9', '0', 'ie'),
                array('XBX', 'Xbox', 'Xbox'))),
            array('Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.2; Win64; x64; Trident/6.0)', array(
                array('IE', 'Internet Explorer', 'IE', '10.0', '10', '0', 'ie'),
                array('WI8', 'Windows 8', 'Win 8'))),
            array('Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)', array(
                array('IE', 'Internet Explorer', 'IE', '10.0', '10', '0', 'ie'),
                array('WI7', 'Windows 7', 'Win 7'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; Trident/6.0)', array(
                array('IE', 'Internet Explorer', 'IE', '10.0', '10', '0', 'ie'),
                array('WI7', 'Windows 7', 'Win 7'))),
            array('Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)', array(
                array('IE', 'Internet Explorer', 'IE', '9.0', '9', '0', 'ie'),
                array('WI7', 'Windows 7', 'Win 7'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Trident/5.0)', array(
                array('IE', 'Internet Explorer', 'IE', '9.0', '9', '0', 'ie'),
                array('WVI', 'Windows Vista', 'Win Vista'))),
            array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.1; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET CLR 3.0.04506; .NET CLR 3.5.21022; InfoPath.2; SLCC1; Zune 3.0)', array(
                array('IE', 'Internet Explorer', 'IE', '8.0', '8', '0', 'ie'),
                array('WI7', 'Windows 7', 'Win 7'))),
            array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0)', array(
                array('IE', 'Internet Explorer', 'IE', '8.0', '8', '0', 'ie'),
                array('WVI', 'Windows Vista', 'Win Vista'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Trident/4.0)', array(
                array('IE', 'Internet Explorer', 'IE', '8.0', '8', '0', 'ie'),
                array('WVI', 'Windows Vista', 'Win Vista'))),
            array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0)', array(
                array('IE', 'Internet Explorer', 'IE', '8.0', '8', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30; .NET CLR 3.0.04506.648)', array(
                array('IE', 'Internet Explorer', 'IE', '6.0', '6', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)', array(
                array('IE', 'Internet Explorer', 'IE', '6.0', '6', '0', 'ie'),
                array('WS3', 'Windows Server 2003 / XP x64', 'Win S2003'))),
            array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; WOW64; SV1; .NET CLR 2.0.50727)', array(
                array('IE', 'Internet Explorer', 'IE', '6.0', '6', '0', 'ie'),
                array('WS3', 'Windows Server 2003 / XP x64', 'Win S2003'))),
            array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; Win64; x64; SV1; .NET CLR 2.0.50727)', array(
                array('IE', 'Internet Explorer', 'IE', '6.0', '6', '0', 'ie'),
                array('WS3', 'Windows Server 2003 / XP x64', 'Win S2003'))),

            // IE Mobile
            array('Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; SAMSUNG; SGH-i917)', array(
                array('IE', 'Internet Explorer', 'IE', '9.0', '9', '0', 'ie'),
                array('WPH', 'Windows Phone OS', 'WinPhone'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; XBLWP7; ZuneWP7)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WPH', 'Windows Phone OS', 'WinPhone'))),
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows Phone OS 7.0; Trident/3.1; IEMobile/7.0; DeviceManufacturer; DeviceModel)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WPH', 'Windows Phone OS', 'WinPhone'))),
            array('Mozilla/4.0 (compatible; MSIE 6.0; Windows CE; IEMobile 7.11)', array(
                array('IE', 'Internet Explorer', 'IE', '6.0', '6', '0', 'ie'),
                array('WMO', 'Windows Mobile', 'WinMo'))),
            array('Mozilla/4.0 (compatible; MSIE 4.01; Windows CE; PPC; 240x320)', array(
                array('IE', 'Internet Explorer', 'IE', '4.01', '4', '01', 'ie'),
                array('WCE', 'Windows CE', 'Win CE'))),

            // Internet Explorer with misbehaving Google Tool Bar
            array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB6.5; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', array(
                array('IE', 'Internet Explorer', 'IE', '8.0', '8', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; GTB0.0; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; .NET CLR 2.0.50727; .NET CLR 3.0.4506.2152; .NET CLR 3.5.30729)', array(
                array('IE', 'Internet Explorer', 'IE', '8.0', '8', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // Iron
            array('Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US) AppleWebKit/531.0 (KHTML, like Gecko) Iron/3.0.189.0 Safari/531.0', array(
                array('IR', 'Iron', 'Iron', '3.0', '3', '0', 'webkit'),
                array('WI7', 'Windows 7', 'Win 7'))),

            // K-Meleon
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.21) Gecko/20090331 K-Meleon/1.5.3', array(
                array('KM', 'K-Meleon', 'K-Meleon', '1.5', '1', '5', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (Darwin; FreeBSD 5.6; en-GB; rv:1.8.1.17pre) Gecko/20080716 K-Meleon/1.5.0', array(
                array('KM', 'K-Meleon', 'K-Meleon', '1.5', '1', '5', 'gecko'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Windows; U; WinNT4.0; en-US; rv:1.2b) Gecko/20021016 K-Meleon 0.7', array(
                array('KM', 'K-Meleon', 'K-Meleon', '0.7', '0', '7', 'gecko'),
                array('WNT', 'Windows NT', 'Win NT'))),

            // Kapiko
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.0.1) Gecko/20080722 Firefox/3.0.1 Kapiko/3.0', array(
                array('KP', 'Kapiko', 'Kapiko', '3.0', '3', '0', 'gecko'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // Kazehakase
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.11) Gecko Kazehakase/0.5.4 Debian/0.5.4-2.1ubuntu3', array(
                array('KZ', 'Kazehakase', 'Kazehakase', '0.5', '0', '5', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.13) Gecko/20080311 (Debian-1.8.1.13+nobinonly-0ubuntu1) Kazehakase/0.5.2', array(
                array('KZ', 'Kazehakase', 'Kazehakase', '0.5', '0', '5', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; Linux x86_64; U;) Gecko/20060207 Kazehakase/0.3.5 Debian/0.3.5-1', array(
                array('KZ', 'Kazehakase', 'Kazehakase', '0.3', '0', '3', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),

            // KKMAN (treat as IE)
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; KKMAN3.2)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // Konqueror
            array('Mozilla/5.0 (compatible; Konqueror/4.0; Linux) KHTML/4.0.5 (like Gecko)', array(
                array('KO', 'Konqueror', 'Konqueror', '4.0', '4', '0', 'khtml'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (compatible; Konqueror/4.0; Microsoft Windows) KHTML/4.0.80 (like Gecko)', array(
                array('KO', 'Konqueror', 'Konqueror', '4.0', '4', '0', 'khtml'),
                false)),
            array('Mozilla/5.0 (compatible; Konqueror/3.5; GNU/kFreeBSD) KHTML/3.5.9 (like Gecko) (Debian)', array(
                array('KO', 'Konqueror', 'Konqueror', '3.5', '3', '5', 'khtml'),
                array('BSD', 'FreeBSD', 'FreeBSD'))),
            array('Mozilla/5.0 (compatible; Konqueror/2.1.1; X11)', array(
                array('KO', 'Konqueror', 'Konqueror', '2.1', '2', '1', 'khtml'),
                false)),

            // Links
            array('Links', array(
                false,
                false)),
            array('Links (2.1pre31; Linux 2.6.21-omap1 armv6l; x)', array(
                array('LI', 'Links', 'Links', '2.1', '2', '1', 'unknown'),
                array('LIN', 'Linux', 'Linux'))),
            array('Links (0.99; OS/2 1 i386; 80x33)', array(
                array('LI', 'Links', 'Links', '0.99', '0', '99', 'unknown'),
                array('OS2', 'OS/2', 'OS/2'))),

            // Lunascape (identity crisis)
            array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US; rv:1.9.1) Gecko/20090701 Firefox/3.5 Lunascape/5.1.2.3', array(
                array('FF', 'Firefox', 'Firefox', '3.5', '3', '5', 'gecko'),
                array('WVI', 'Windows Vista', 'Win Vista'))),
            array('Mozilla/5.0 (Windows; U; ; cs-CZ) AppleWebKit/532+ (KHTML, like Gecko, Safari/532.0) Lunascape/5.1.2.3', array(
                array('SF', 'Safari', 'Safari', '4.0', '4', '0', 'webkit'),
                false)),
            array('Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; InfoPath.1; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Lunascape 5.1.2.3)', array(
                array('IE', 'Internet Explorer', 'IE', '6.0', '6', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // Lynx
            array('Lynx (textmode)', array(
                false,
                false)),
            array('Lynx/2.8.7dev.9 libwww-FM/2.14', array(
                array('LX', 'Lynx', 'Lynx', '2.8', '2', '8', 'unknown'),
                false)),

            // Maxathon (treat as IE)
            array('Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; SV1; Maxthon; .NET CLR 1.1.4322)', array(
                array('IE', 'Internet Explorer', 'IE', '7.0', '7', '0', 'ie'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // Midori
            array('Midori/0.1.9 (X11; Linux i686; U; fr-fr) WebKit/532+', array(
                array('MI', 'Midori', 'Midori', '0.1', '0', '1', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Linux; pt-br) AppleWebKit/531+ (KHTML, like Gecko) Safari/531.2+ Midori/0.3', array(
                array('MI', 'Midori', 'Midori', '0.3', '0', '3', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),

            // Mozilla Suite
            array('Mozilla/5.0 (X11; U; SunOS sun4u; en-US; rv:1.7) Gecko/20070606', array(
                array('MO', 'Mozilla', 'Mozilla', '1.7', '1', '7', 'gecko'),
                array('SOS', 'SunOS', 'SunOS'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.8) Gecko/20050927 Debian/1.7.8-1sarge3', array(
                array('MO', 'Mozilla', 'Mozilla', '1.7', '1', '7', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),

            // NCSA Mosaic
            array('PATHWORKS Mosaic/1.0 libwww/2.15_Spyglass', array(
                array('MC', 'NCSA Mosaic', 'Mosaic', '1.0', '1', '0', 'unknown'),
                false)),
            array('WinMosaic/Version 2.0 (ALPHA 2)', array(
                array('MC', 'NCSA Mosaic', 'Mosaic', '2.0', '2', '0', 'unknown'),
                false)),
            array('VMS_Mosaic/3.8-1 (Motif;OpenVMS V7.3-2 DEC 3000 - M700) libwww/2.12_Mosaic', array(
                array('MC', 'NCSA Mosaic', 'Mosaic', '3.8', '3', '8', 'unknown'),
                array('VMS', 'OpenVMS', 'OpenVMS'))),
            array('Mosaic from Digital/1.02_Win32', array(
                array('MC', 'NCSA Mosaic', 'Mosaic', '1.02', '1', '02', 'unknown'),
                array('W95', 'Windows 95', 'Win 95'))),
            array('NCSA Mosaic/2.0.0b4 (Windows AXP)', array(
                array('MC', 'NCSA Mosaic', 'Mosaic', '2.0', '2', '0', 'unknown'),
                false)),
            array('NCSA_Mosaic/2.7b5 (X11;Linux 2.6.7 i686) libwww/2.12 modified', array(
                array('MC', 'NCSA Mosaic', 'Mosaic', '2.7', '2', '7', 'unknown'),
                array('LIN', 'Linux', 'Linux'))),
            array('mMosaic/3.6.6 (X11;SunOS 5.8 sun4m)', array(
                array('MC', 'NCSA Mosaic', 'Mosaic', '3.6', '3', '6', 'unknown'),
                array('SOS', 'SunOS', 'SunOS'))),

            // Netscape Navigator (9.x)
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.8pre) Gecko/20071015 Firefox/2.0.0.7 Navigator/9.0', array(
                array('NS', 'Netscape', 'Netscape', '9.0', '9', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.5) Gecko/20070321 Netscape/9.0', array(
                array('NS', 'Netscape', 'Netscape', '9.0', '9', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),

            // Netscape (6.x - 8.x)
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.7.5) Gecko/20070321 Netscape/8.1.3', array(
                array('NS', 'Netscape', 'Netscape', '8.1', '8', '1', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7.2) Gecko/20040804 Netscape/7.2 (ax)', array(
                array('NS', 'Netscape', 'Netscape', '7.2', '7', '2', 'gecko'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (X11; U; OSF1 alpha; en-US; rv:0.9.4.1) Gecko/20020517 Netscape6/6.2.3', array(
                array('NS', 'Netscape', 'Netscape', '6.2', '6', '2', 'gecko'),
                array('T64', 'Tru64', 'Tru64'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:0.9.2) Gecko/20010726 Netscape6/6.1', array(
                array('NS', 'Netscape', 'Netscape', '6.1', '6', '1', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),

            // Netscape Communicator (4.x)
            array('Mozilla/4.76C-SGI [en] (X11; I; IRIX64 6.5 IP30)', array(
                array('NS', 'Netscape', 'Netscape', '4.76', '4', '76', 'gecko'),
                array('IRI', 'IRIX', 'IRIX'))),
            array('Mozilla/4.72 [en] (X11; I; HP-UX B.11.00 9000/800)', array(
                array('NS', 'Netscape', 'Netscape', '4.72', '4', '72', 'gecko'),
                array('HPX', 'HP-UX', 'HP-UX'))),
            array('Mozilla/4.41 (BEOS; U ;Nav)', array(
                array('NS', 'Netscape', 'Netscape', '4.41', '4', '41', 'gecko'),
                array('BEO', 'BeOS', 'BeOS'))),
            array('Mozilla/4.0 (compatible; Windows NT 5.1; U; en)', array(
                array('NS', 'Netscape', 'Netscape', '4.0', '4', '0', 'gecko'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // Netscape Navigator (up to 3.x)
            array('Mozilla/3.0 (X11; I; AIX 2)', array(
                array('NS', 'Netscape', 'Netscape', '3.0', '3', '0', 'gecko'),
                array('AIX', 'AIX', 'AIX'))),
            array('Mozilla/2.02 [fr] (WinNT; I)', array(
                array('NS', 'Netscape', 'Netscape', '2.02', '2', '02', 'gecko'),
                array('WNT', 'Windows NT', 'Win NT'))),

            // NetFront NX
            array('Mozilla/5.0 (Nintendo WiiU) AppleWebKit/534.52 (KHTML, like Gecko) NX/2.1.0.8.21 NintendoBrowser/1.0.0.7494.US', array(
                array('NF', 'NetFront', 'NetFront', '2.1', '2', '1', 'webkit'),
                array('WIU', 'Nintendo Wii U', 'Wii U'))),
            array('Mozilla/5.0 (Nintendo 3DS; U; ; en) Version/1.7498.US', array(
                array('NF', 'NetFront', 'NetFront', '1.7498', '1', '7498', 'webkit'),
                array('3DS', 'Nintendo 3DS', '3DS'))),
            array('Mozilla/5.0 (Playstation Vita 1.61) AppleWebKit/531.22.8 (KHTML, like Gecko) Silk/3.2', array(
                array('NF', 'NetFront', 'NetFront', '3.2', '3', '2', 'webkit'),
                array('PSV', 'PlayStation Vita', 'PS Vita'))),

            // Kindle
            array('Mozilla/4.0 (compatible; Linux 2.6.10) NetFront/3.3 Kindle/1.0 (screen 600x800)', array(
                array('NF', 'NetFront', 'NetFront', '3.3', '3', '3', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (Linux; U; Android 2.3.4; en-us; Kindle Fire Build/GINGERBREAD) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1', array(
                array('SF', 'Safari', 'Safari', '4.0', '4', '0', 'webkit'),
                array('AND', 'Android', 'Android'))),
            array('Mozilla/5.0 (Linux; U; en-US) AppleWebKit/528.5+ (KHTML, like Gecko, Safari/528.5+) Version/4.0 Kindle/3.0 (screen 600×800; rotate)', array(
                array('SF', 'Safari', 'Safari', '4.0', '4', '0', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_3; en-us; Silk/1.1.0-80) AppleWebKit/533.16 (KHTML, like Gecko) Version/5.0 Safari/533.16 Silk-Accelerated=true', array(
                array('SF', 'Safari', 'Safari', '5.0', '5', '0', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),

            // Omniweb
            array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X; en-US) AppleWebKit/525.18 (KHTML, like Gecko, Safari/525.20) OmniWeb/v622.3.0.105198', array(
                array('OW', 'OmniWeb', 'OmniWeb', '5.8', '5', '8', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/522+ (KHTML, like Gecko, Safari/522) OmniWeb/v613', array(
                array('OW', 'OmniWeb', 'OmniWeb', '5.6', '5', '6', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/420+ (KHTML, like Gecko, Safari/420) OmniWeb/v607', array(
                array('OW', 'OmniWeb', 'OmniWeb', '5.5', '5', '5', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/125.4 (KHTML, like Gecko, Safari) OmniWeb/v563.34', array(
                array('OW', 'OmniWeb', 'OmniWeb', '5.1', '5', '1', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/85 (KHTML, like Gecko) OmniWeb/v558.36', array(
                array('OW', 'OmniWeb', 'OmniWeb', '5.0', '5', '0', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en-US) AppleWebKit/85 (KHTML, like Gecko) OmniWeb/v496', array(
                array('OW', 'OmniWeb', 'OmniWeb', '4.5', '4', '5', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),

            // Opera
            array('Opera/9.63 (Windows NT 5.1; U; en) Presto/2.1.1', array(
                array('OP', 'Opera', 'Opera', '9.63', '9', '63', 'opera'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Opera/9.30 (Nintendo Wii; U; ; 2047-7; en)', array(
                array('OP', 'Opera', 'Opera', '9.30', '9', '30', 'opera'),
                array('WII', 'Nintendo Wii', 'Wii'))),
            array('Opera/9.64 (Windows ME; U; en) Presto/2.1.1', array(
                array('OP', 'Opera', 'Opera', '9.64', '9', '64', 'opera'),
                array('WME', 'Windows Me', 'Win Me'))),
            array('Opera/9.80 (Windows NT 5.1; U; en) Presto/2.2.15 Version/10.00', array(
                array('OP', 'Opera', 'Opera', '10.00', '10', '0', 'opera'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/4.0 (compatible; MSIE 6.0; Nitro) Opera 8.50 [en Mozilla/4.0 (compatible; MSIE 6.0; Nitro) Opera 8.50 [ja]', array(
                array('OP', 'Opera', 'Opera', '8.50', '8', '50', 'opera'),
                array('NDS', 'Nintendo DS', 'DS'))),
            array('Opera/9.00 (Nintendo DS U; ; 1309-9; de)', array(
                array('OP', 'Opera', 'Opera', '9.00', '9', '00', 'opera'),
                array('NDS', 'Nintendo DS', 'DS'))),
            array('Opera/9.50 (Nintendo DSi; Opera/507; U; en-US) ', array(
                array('OP', 'Opera', 'Opera', '9.50', '9', '50', 'opera'),
                array('DSI', 'Nintendo DSi', 'DSi'))),

            // PlayStation
            array('Mozilla/5.0 (PLAYSTATION 3; 1.00)', array(
                false,
                array('PS3', 'PlayStation 3', 'PS3'))),

            // PSP
            array('PSP (PlayStation Portable); 2.00', array(
                false,
                array('PSP', 'PlayStation Portable', 'PSP'))),
            array('Mozilla/4.0 (PSP (PlayStation Portable); 2.00)', array(
                false,
                array('PSP', 'PlayStation Portable', 'PSP'))),

            // Rekonq 1.0+
            array('Mozilla/5.0 (X11; Linux i686) AppleWebKit/534.34 (KHTML, like Gecko) rekonq/1.0 Safari/534.34', array(
                array('RK', 'Rekonq', 'Rekonq', '1.0', '1', '0', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),

            // Safari
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.19 (KHTML, like Gecko) Version/3.1.2 Safari/525.21', array(
                array('SF', 'Safari', 'Safari', '3.1', '3', '1', 'webkit'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (iPhone; U; CPU iPhone OS 2_2 like Mac OS X; en-us) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5G77 Safari/525.20', array(
                array('SF', 'Safari', 'Safari', '3.1', '3', '1', 'webkit'),
                array('IPH', 'iPhone', 'iPhone'))),
            array('Mozilla/5.0 (iPod; U; CPU like Mac OS X; en) AppleWebKit/420.1 (KHTML, like Gecko) Version/3.0 Mobile/3A100a Safari/419.3', array(
                array('SF', 'Safari', 'Safari', '3.0', '3', '0', 'webkit'),
                array('IPD', 'iPod', 'iPod'))),
            array('Mozilla/5.0 (iPod; U; CPU iPhone OS 2_2_1 like Mac OS X; en-us) AppleWebKit/525.18.1 (KHTML, like Gecko) Version/3.1.1 Mobile/5H11a Safari/525.20', array(
                array('SF', 'Safari', 'Safari', '3.1', '3', '1', 'webkit'),
                array('IPD', 'iPod', 'iPod'))),
            array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_5; en-us) AppleWebKit/527.3+ (KHTML, like Gecko) Version/3.1.2 Safari/525.20.1', array(
                array('SF', 'Safari', 'Safari', '3.1', '3', '1', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (iPad; U; CPU iPhone OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B314 Safari/531.21.10', array(
                array('SF', 'Safari', 'Safari', '4.0', '4', '0', 'webkit'),
                array('IPA', 'iPad', 'iPad'))),
            array('Mozilla/5.0 (iPad; U; CPU OS 3_2 like Mac OS X; en-us) AppleWebKit/531.21.10 (KHTML, like Gecko) Version/4.0.4 Mobile/7B367 Safari/531.21.10', array(
                array('SF', 'Safari', 'Safari', '4.0', '4', '0', 'webkit'),
                array('IPA', 'iPad', 'iPad'))),
            array('Mozilla/5.0 (iPod; U; CPU iPhone OS 4_0 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7', array(
                array('SF', 'Safari', 'Safari', '4.0', '4', '0', 'webkit'),
                array('IPD', 'iPod', 'iPod'))),
            array('Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0 like Mac OS X; en-us) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7', array(
                array('SF', 'Safari', 'Safari', '4.0', '4', '0', 'webkit'),
                array('IPH', 'iPhone', 'iPhone'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 6.0; en-US) AppleWebKit/533.19.4 (KHTML, like Gecko) ', array(
                array('SF', 'Safari', 'Safari', '5.0', '5', '0', 'webkit'),
                array('WVI', 'Windows Vista', 'Win Vista'))),
            array('Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/534.50 (KHTML, like Gecko) Version/5.1 Safari/534.50', array(
                array('SF', 'Safari', 'Safari', '5.1', '5', '1', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (iPhone; CPU iPhone OS 6_1 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Mobile/10B141', array(
                array('SF', 'Safari', 'Safari', '6.0', '6', '0', 'webkit'),
                array('IPH', 'iPhone', 'iPhone'))),
            array('Mozilla/5.0 (iPhone; CPU iPhone OS 6_1 like Mac OS X) AppleWebKit/536.26 (KHTML, like Gecko) Version/6.0 Mobile/10B141 Safari/8536.25', array(
                array('SF', 'Safari', 'Safari', '6.0', '6', '0', 'webkit'),
                array('IPH', 'iPhone', 'iPhone'))),

            // SeaMonkey (formerly Mozilla Suite and rebranded versions)
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.1.8) Gecko/20071008 Iceape/1.1.5 (Ubuntu-1.1.5-1ubuntu0.7.10)', array(
                array('SM', 'SeaMonkey', 'SeaMonkey', '1.1', '1', '1', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.1b4pre) Gecko/20090405 SeaMonkey/2.0b1pre', array(
                array('SM', 'SeaMonkey', 'SeaMonkey', '2.0', '2', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9) Gecko', array(
                // this pre-2.0 UA is missing the SeaMonkey/X.Y
                array('SM', 'SeaMonkey', 'SeaMonkey', '1.9', '1', '9', 'gecko'),
                array('WXP', 'Windows XP', 'Win XP'))),
            array('Mozilla/5.0 (Windows; U; Windows NT 5.1; cs; rv:1.9) Gecko/2008052906', array(
                // this pre-2.0 UA is missing the SeaMonkey/X.Y
                array('SM', 'SeaMonkey', 'SeaMonkey', '1.9', '1', '9', 'gecko'),
                array('WXP', 'Windows XP', 'Win XP'))),

            // Palm webOS
            array('Mozilla/5.0 (webOS/1.0; U; en-us) AppleWebKit/525.27.1 (KHTML, like Gecko) Version/1.0 Safari/525.27.1 Pre/1.0', array(
                array('WO', 'Palm webOS', 'webOS', '1.0', '1', '0', 'webkit'),
                array('WOS', 'Palm webOS', 'webOS'))),
            array('Mozilla/5.0 (webOS/Palm webOS 1.2.9; U; en-US) AppleWebKit/525.27.1 (KHTML, like Gecko) Version/1.0 Safari/525.27.1 Pixi/1.0', array(
                array('WO', 'Palm webOS', 'webOS', '1.0', '1', '0', 'webkit'),
                array('WOS', 'Palm webOS', 'webOS'))),
            array('Mozilla/5.0 [en] (PalmOS; U; WebPro/3.5; Palm-Zi72)', array(
                array('WP', 'WebPro', 'WebPro', '3.5', '3', '5', 'unknown'),
                array('POS', 'Palm OS', 'Palm OS'))),

            // Palm WebPro
            array('Mozilla/4.76 [en] (PalmOS; U; WebPro/3.0.1a; Palm-Cct1)', array(
                array('WP', 'WebPro', 'WebPro', '3.0', '3', '0', 'unknown'),
                array('POS', 'Palm OS', 'Palm OS'))),
            array('Mozilla/4.76 [en] (PalmOS; U; WebPro/3.0; Palm-Arz1)', array(
                array('WP', 'WebPro', 'WebPro', '3.0', '3', '0', 'unknown'),
                array('POS', 'Palm OS', 'Palm OS'))),

            // Shiira 1.x - treat as Safari since it uses the installed version of Safari's WebKit
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; en) AppleWebKit/417.9 (KHTML, like Gecko, Safari) Shiira/1.1', array(
                array('SF', 'Safari', 'Safari', '2.0', '2', '0', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),
            array('Mozilla/5.0 (Macintosh; U; PPC Mac OS X; pl-pl) AppleWebKit/312.8 (KHTML, like Gecko) Shiira/1.2.1 Safari/125', array(
                array('SF', 'Safari', 'Safari', '1.3', '1', '3', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),

            // Shiira 2.x - ditto
            array('Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_5_7; en-us) AppleWebKit/525.28.3 (KHTML, like Gecko) Shiira Safari/125', array(
                array('SF', 'Safari', 'Safari', '3.2', '3', '2', 'webkit'),
                array('MAC', 'Mac OS', 'Mac OS'))),

            // SymbianOS
            array('Nokia3650/1.0 SymbianOS/6.1 Series60/1.2 Profile/MIDP-1.0 Configuration/CLDC-1.0', array(
                false,
                array('SYM', 'SymbianOS', 'SymbianOS'))),
            array('Mozilla/5.0 (SymbianOS/9.4; U; Series60/5.0 Nokia5800d-1b/20.2.014; Profile/MIDP-2.1 Configuration/CLDC-1.1 ) AppleWebKit/413 (KHTML, like Gecko) Safari/413', array(
                array('SF', 'Safari', 'Safari', '2.0', '2', '0', 'webkit'),
                array('SYM', 'SymbianOS', 'SymbianOS'))),
            array('Opera/9.80 (S60; SymbOS; Opera Mobi/499; U; en-GB) Presto/2.4.18 Version/10.00', array(
                array('OP', 'Opera', 'Opera', '10.00', '10', '00', 'opera'),
                array('SYM', 'SymbianOS', 'SymbianOS'))),
            array('SonyEricssonG700/R100 Mozilla/4.0 (compatible; MSIE 6.0; Symbian OS; 958) Opera 8.65 [ru]', array(
                array('OP', 'Opera', 'Opera', '8.65', '8', '65', 'opera'),
                array('SYM', 'SymbianOS', 'SymbianOS'))),

            // Appcelerator Titanium
            array('Appcelerator Titanium/1.8.0 (iPhone Simulator/4.3; iPhone OS; en_US;)', array(
                array('TI', 'Titanium', 'Titanium', '1.8', '1', '8', 'webkit'),
                array('IPH', 'iPhone', 'iPhone'))),

            array('Appcelerator Titanium/1.8.0 (iPod touch/4.3.1; iPhone OS; de_DE;)', array(
                array('TI', 'Titanium', 'Titanium', '1.8', '1', '8', 'webkit'),
                array('IPD', 'iPod', 'iPod'))),

            array('Appcelerator Titanium/1.8.0 (iPad/4.3.3; iPhone OS; de_DE;)', array(
                array('TI', 'Titanium', 'Titanium', '1.8', '1', '8', 'webkit'),
                array('IPA', 'iPad', 'iPad'))),

            array('Dalvik/1.1.0 (Linux; U; Android 2.1; google_sdk Build/ERD79) Titanium/1.8.0', array(
                array('TI', 'Titanium', 'Titanium', '1.8', '1', '8', 'webkit'),
                array('AND', 'Android', 'Android'))),

            array('Dalvik/1.4.0 (Linux; U; Android 2.3.3; GT-I9100 Build/GINGERBREAD) Titanium/1.8.0', array(
                array('TI', 'Titanium', 'Titanium', '1.8', '1', '8', 'webkit'),
                array('AND', 'Android', 'Android'))),

            array('Mozilla/5.0 (X11; U; CrOS i686 9.10.0; en-US) AppleWebKit/532.5 (KHTML, like Gecko) Chrome/4.0.253.0 Safari/532.5', array(
                array('CH', 'Chrome', 'Chrome', '4.0', '4', '0', 'webkit'),
                array('LIN', 'Linux', 'Linux'))),

            // Email Clients

            // Thunderbird
            array('Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20120713 Thunderbird/14.0 Lightning/1.6', array(
                array('TB', 'Thunderbird', 'Thunderbird', '14.0', '14', '0', 'gecko'),
                array('WXP', 'Windows XP', 'Win XP'))),

            array('Mozilla/5.0 (X11; Linux i686; rv:16.0) Gecko/20121011 Thunderbird/16.0.1', array(
                array('TB', 'Thunderbird', 'Thunderbird', '16.0', '16', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux'))),

            array('Mozilla/5.0 (Windows NT 6.1; WOW64; rv:17.0) Gecko/20130107 Thunderbird/17.0.2 Lightning/1.9', array(
                array('TB', 'Thunderbird', 'Thunderbird', '17.0', '17', '0', 'gecko'),
                array('WI7', 'Windows 7', 'Win 7'))),

            array('Mozilla/5.0 (X11; Linux i686 on x86_64; rv:15.0) Gecko/20120907 Thunderbird/15.0.1', array(
                array('TB', 'Thunderbird', 'Thunderbird', '15.0', '15', '0', 'gecko'),
                array('LIN', 'Linux', 'Linux')))

        );
    }

    /**
     * Test getBrowser()
     *
     * @dataProvider getUserAgents
     * @group Plugins
     */
    public function testGetBrowser($userAgent, $expected)
    {
        $res = UserAgentParser::getBrowser($userAgent);
        $family = false;

        if ($res === false)
            $this->assertFalse($expected[0]);
        else {
            $family = \Piwik\Plugins\UserSettings\getBrowserFamily($res['id']);
            $this->assertEquals($expected[0][0], $res['id']);
            $this->assertEquals($expected[0][1], $res['name']);
            $this->assertEquals($expected[0][2], $res['short_name']);
            $this->assertEquals($expected[0][3], $res['version']);
            $this->assertEquals($expected[0][4], $res['major_number']);
            $this->assertEquals($expected[0][5], $res['minor_number']);
            $this->assertEquals($expected[0][6], $family);
        }
    }

    /**
     * Test getOperatingSystem()
     *
     * @dataProvider getUserAgents
     * @group Plugins
     */
    public function testGetOperatingSystem($userAgent, $expected)
    {
        $res = UserAgentParser::getOperatingSystem($userAgent);

        $this->assertEquals($expected[1][0], $res['id']);
        $this->assertEquals($expected[1][1], $res['name']);
        $this->assertEquals($expected[1][2], $res['short_name']);
    }
}
