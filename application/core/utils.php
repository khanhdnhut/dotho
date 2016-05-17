<?php

class Utils
{

    static $format_info = array();

    public static function convertTVKhongDau($str)
    {
        // In thường
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        // In đậm
        $str = preg_replace("/(À|Á|Ạ|Ả|Ã|Â|Ầ|Ấ|Ậ|Ẩ|Ẫ|Ă|Ằ|Ắ|Ặ|Ẳ|Ẵ)/", 'A', $str);
        $str = preg_replace("/(È|É|Ẹ|Ẻ|Ẽ|Ê|Ề|Ế|Ệ|Ể|Ễ)/", 'E', $str);
        $str = preg_replace("/(Ì|Í|Ị|Ỉ|Ĩ)/", 'I', $str);
        $str = preg_replace("/(Ò|Ó|Ọ|Ỏ|Õ|Ô|Ồ|Ố|Ộ|Ổ|Ỗ|Ơ|Ờ|Ớ|Ợ|Ở|Ỡ)/", 'O', $str);
        $str = preg_replace("/(Ù|Ú|Ụ|Ủ|Ũ|Ư|Ừ|Ứ|Ự|Ử|Ữ)/", 'U', $str);
        $str = preg_replace("/(Ỳ|Ý|Ỵ|Ỷ|Ỹ)/", 'Y', $str);
        $str = preg_replace("/(Đ)/", 'D', $str);
        return $str;
    }

    public static function createSlug($str)
    {
        if ($str != NULL && $str != "") {
            $slug = strtolower(Utils::convertTVKhongDau(strtolower(preg_replace('/\s+/', '-', $str))));
            return $slug;
        }
        return NULL;
    }

    /**
     * Class casting
     *
     * @param string|object $destination
     * @param object $sourceObject
     * @return object
     */
    public static function cast($destination, $sourceObject)
    {
        if (is_string($destination)) {
            $destination = new $destination();
        }
        $sourceReflection = new ReflectionObject($sourceObject);
        $destinationReflection = new ReflectionObject($destination);
        $sourceProperties = $sourceReflection->getProperties();
        foreach ($sourceProperties as $sourceProperty) {
            $sourceProperty->setAccessible(true);
            $name = $sourceProperty->getName();
            $value = $sourceProperty->getValue($sourceObject);
            if ($destinationReflection->hasProperty($name)) {
                $propDest = $destinationReflection->getProperty($name);
                $propDest->setAccessible(true);
                $propDest->setValue($destination, $value);
            } else {
                $destination->$name = $value;
            }
        }
        return $destination;
    }

    public static function deleteFile($filePath)
    {
        try {
            if (Utils::checkFileExist($filePath)) {
                unlink($filePath);
            }
        } catch (Exception $e) {
            return FALSE;
        }
        return TRUE;
    }

    public static function getFileFormatArray()
    {
        if (empty(Utils::$format_info)) {
            Utils::$format_info = array(
                // Audio formats
                // AC-3   - audio      - Dolby AC-3 / Dolby Digital
                'ac3' => array(
                    'pattern' => '^\x0B\x77',
                    'group' => 'audio',
                    'module' => 'ac3',
                    'mime_type' => 'audio/ac3',
                ),
                // AAC  - audio       - Advanced Audio Coding (AAC) - ADIF format
                'adif' => array(
                    'pattern' => '^ADIF',
                    'group' => 'audio',
                    'module' => 'aac',
                    'mime_type' => 'application/octet-stream',
                    'fail_ape' => 'WARNING',
                ),
                /*
                  // AA   - audio       - Audible Audiobook
                  'aa'   => array(
                  'pattern'   => '^.{4}\x57\x90\x75\x36',
                  'group'     => 'audio',
                  'module'    => 'aa',
                  'mime_type' => 'audio/audible',
                  ),
                 */
                // AAC  - audio       - Advanced Audio Coding (AAC) - ADTS format (very similar to MP3)
                'adts' => array(
                    'pattern' => '^\xFF[\xF0-\xF1\xF8-\xF9]',
                    'group' => 'audio',
                    'module' => 'aac',
                    'mime_type' => 'application/octet-stream',
                    'fail_ape' => 'WARNING',
                ),
                // AU   - audio       - NeXT/Sun AUdio (AU)
                'au' => array(
                    'pattern' => '^\.snd',
                    'group' => 'audio',
                    'module' => 'au',
                    'mime_type' => 'audio/basic',
                ),
                // AMR  - audio       - Adaptive Multi Rate
                'amr' => array(
                    'pattern' => '^\x23\x21AMR\x0A', // #!AMR[0A]
                    'group' => 'audio',
                    'module' => 'amr',
                    'mime_type' => 'audio/amr',
                ),
                // AVR  - audio       - Audio Visual Research
                'avr' => array(
                    'pattern' => '^2BIT',
                    'group' => 'audio',
                    'module' => 'avr',
                    'mime_type' => 'application/octet-stream',
                ),
                // BONK - audio       - Bonk v0.9+
                'bonk' => array(
                    'pattern' => '^\x00(BONK|INFO|META| ID3)',
                    'group' => 'audio',
                    'module' => 'bonk',
                    'mime_type' => 'audio/xmms-bonk',
                ),
                // DSS  - audio       - Digital Speech Standard
                'dss' => array(
                    'pattern' => '^[\x02-\x03]ds[s2]',
                    'group' => 'audio',
                    'module' => 'dss',
                    'mime_type' => 'application/octet-stream',
                ),
                // DTS  - audio       - Dolby Theatre System
                'dts' => array(
                    'pattern' => '^\x7F\xFE\x80\x01',
                    'group' => 'audio',
                    'module' => 'dts',
                    'mime_type' => 'audio/dts',
                ),
                // FLAC - audio       - Free Lossless Audio Codec
                'flac' => array(
                    'pattern' => '^fLaC',
                    'group' => 'audio',
                    'module' => 'flac',
                    'mime_type' => 'audio/x-flac',
                ),
                // LA   - audio       - Lossless Audio (LA)
                'la' => array(
                    'pattern' => '^LA0[2-4]',
                    'group' => 'audio',
                    'module' => 'la',
                    'mime_type' => 'application/octet-stream',
                ),
                // LPAC - audio       - Lossless Predictive Audio Compression (LPAC)
                'lpac' => array(
                    'pattern' => '^LPAC',
                    'group' => 'audio',
                    'module' => 'lpac',
                    'mime_type' => 'application/octet-stream',
                ),
                // MIDI - audio       - MIDI (Musical Instrument Digital Interface)
                'midi' => array(
                    'pattern' => '^MThd',
                    'group' => 'audio',
                    'module' => 'midi',
                    'mime_type' => 'audio/midi',
                ),
                // MAC  - audio       - Monkey's Audio Compressor
                'mac' => array(
                    'pattern' => '^MAC ',
                    'group' => 'audio',
                    'module' => 'monkey',
                    'mime_type' => 'application/octet-stream',
                ),
// has been known to produce false matches in random files (e.g. JPEGs), leave out until more precise matching available
//				// MOD  - audio       - MODule (assorted sub-formats)
//				'mod'  => array(
//							'pattern'   => '^.{1080}(M\\.K\\.|M!K!|FLT4|FLT8|[5-9]CHN|[1-3][0-9]CH)',
//							'group'     => 'audio',
//							'module'    => 'mod',
//							'option'    => 'mod',
//							'mime_type' => 'audio/mod',
//						),
                // MOD  - audio       - MODule (Impulse Tracker)
                'it' => array(
                    'pattern' => '^IMPM',
                    'group' => 'audio',
                    'module' => 'mod',
                    //'option'    => 'it',
                    'mime_type' => 'audio/it',
                ),
                // MOD  - audio       - MODule (eXtended Module, various sub-formats)
                'xm' => array(
                    'pattern' => '^Extended Module',
                    'group' => 'audio',
                    'module' => 'mod',
                    //'option'    => 'xm',
                    'mime_type' => 'audio/xm',
                ),
                // MOD  - audio       - MODule (ScreamTracker)
                's3m' => array(
                    'pattern' => '^.{44}SCRM',
                    'group' => 'audio',
                    'module' => 'mod',
                    //'option'    => 's3m',
                    'mime_type' => 'audio/s3m',
                ),
                // MPC  - audio       - Musepack / MPEGplus
                'mpc' => array(
                    'pattern' => '^(MPCK|MP\+|[\x00\x01\x10\x11\x40\x41\x50\x51\x80\x81\x90\x91\xC0\xC1\xD0\xD1][\x20-37][\x00\x20\x40\x60\x80\xA0\xC0\xE0])',
                    'group' => 'audio',
                    'module' => 'mpc',
                    'mime_type' => 'audio/x-musepack',
                ),
                // MP3  - audio       - MPEG-audio Layer 3 (very similar to AAC-ADTS)
                'mp3' => array(
                    'pattern' => '^\xFF[\xE2-\xE7\xF2-\xF7\xFA-\xFF][\x00-\x0B\x10-\x1B\x20-\x2B\x30-\x3B\x40-\x4B\x50-\x5B\x60-\x6B\x70-\x7B\x80-\x8B\x90-\x9B\xA0-\xAB\xB0-\xBB\xC0-\xCB\xD0-\xDB\xE0-\xEB\xF0-\xFB]',
                    'group' => 'audio',
                    'module' => 'mp3',
                    'mime_type' => 'audio/mpeg',
                ),
                // OFR  - audio       - OptimFROG
                'ofr' => array(
                    'pattern' => '^(\*RIFF|OFR)',
                    'group' => 'audio',
                    'module' => 'optimfrog',
                    'mime_type' => 'application/octet-stream',
                ),
                // RKAU - audio       - RKive AUdio compressor
                'rkau' => array(
                    'pattern' => '^RKA',
                    'group' => 'audio',
                    'module' => 'rkau',
                    'mime_type' => 'application/octet-stream',
                ),
                // SHN  - audio       - Shorten
                'shn' => array(
                    'pattern' => '^ajkg',
                    'group' => 'audio',
                    'module' => 'shorten',
                    'mime_type' => 'audio/xmms-shn',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // TTA  - audio       - TTA Lossless Audio Compressor (http://tta.corecodec.org)
                'tta' => array(
                    'pattern' => '^TTA', // could also be '^TTA(\x01|\x02|\x03|2|1)'
                    'group' => 'audio',
                    'module' => 'tta',
                    'mime_type' => 'application/octet-stream',
                ),
                // VOC  - audio       - Creative Voice (VOC)
                'voc' => array(
                    'pattern' => '^Creative Voice File',
                    'group' => 'audio',
                    'module' => 'voc',
                    'mime_type' => 'audio/voc',
                ),
                // VQF  - audio       - transform-domain weighted interleave Vector Quantization Format (VQF)
                'vqf' => array(
                    'pattern' => '^TWIN',
                    'group' => 'audio',
                    'module' => 'vqf',
                    'mime_type' => 'application/octet-stream',
                ),
                // WV  - audio        - WavPack (v4.0+)
                'wv' => array(
                    'pattern' => '^wvpk',
                    'group' => 'audio',
                    'module' => 'wavpack',
                    'mime_type' => 'application/octet-stream',
                ),
                // Audio-Video formats
                // ASF  - audio/video - Advanced Streaming Format, Windows Media Video, Windows Media Audio
                'asf' => array(
                    'pattern' => '^\x30\x26\xB2\x75\x8E\x66\xCF\x11\xA6\xD9\x00\xAA\x00\x62\xCE\x6C',
                    'group' => 'audio-video',
                    'module' => 'asf',
                    'mime_type' => 'video/x-ms-asf',
                    'iconv_req' => false,
                ),
                // BINK - audio/video - Bink / Smacker
                'bink' => array(
                    'pattern' => '^(BIK|SMK)',
                    'group' => 'audio-video',
                    'module' => 'bink',
                    'mime_type' => 'application/octet-stream',
                ),
                // FLV  - audio/video - FLash Video
                'flv' => array(
                    'pattern' => '^FLV\x01',
                    'group' => 'audio-video',
                    'module' => 'flv',
                    'mime_type' => 'video/x-flv',
                ),
                // MKAV - audio/video - Mastroka
                'matroska' => array(
                    'pattern' => '^\x1A\x45\xDF\xA3',
                    'group' => 'audio-video',
                    'module' => 'matroska',
                    'mime_type' => 'video/x-matroska', // may also be audio/x-matroska
                ),
                // MPEG - audio/video - MPEG (Moving Pictures Experts Group)
                'mpeg' => array(
                    'pattern' => '^\x00\x00\x01(\xBA|\xB3)',
                    'group' => 'audio-video',
                    'module' => 'mpeg',
                    'mime_type' => 'video/mpeg',
                ),
                // NSV  - audio/video - Nullsoft Streaming Video (NSV)
                'nsv' => array(
                    'pattern' => '^NSV[sf]',
                    'group' => 'audio-video',
                    'module' => 'nsv',
                    'mime_type' => 'application/octet-stream',
                ),
                // Ogg  - audio/video - Ogg (Ogg-Vorbis, Ogg-FLAC, Speex, Ogg-Theora(*), Ogg-Tarkin(*))
                'ogg' => array(
                    'pattern' => '^OggS',
                    'group' => 'audio',
                    'module' => 'ogg',
                    'mime_type' => 'application/ogg',
                    'fail_id3' => 'WARNING',
                    'fail_ape' => 'WARNING',
                ),
                // QT   - audio/video - Quicktime
                'quicktime' => array(
                    'pattern' => '^.{4}(cmov|free|ftyp|mdat|moov|pnot|skip|wide)',
                    'group' => 'audio-video',
                    'module' => 'quicktime',
                    'mime_type' => 'video/quicktime',
                ),
                // RIFF - audio/video - Resource Interchange File Format (RIFF) / WAV / AVI / CD-audio / SDSS = renamed variant used by SmartSound QuickTracks (www.smartsound.com) / FORM = Audio Interchange File Format (AIFF)
                'riff' => array(
                    'pattern' => '^(RIFF|SDSS|FORM)',
                    'group' => 'audio-video',
                    'module' => 'riff',
                    'mime_type' => 'audio/x-wave',
                    'fail_ape' => 'WARNING',
                ),
                // Real - audio/video - RealAudio, RealVideo
                'real' => array(
                    'pattern' => '^(\\.RMF|\\.ra)',
                    'group' => 'audio-video',
                    'module' => 'real',
                    'mime_type' => 'audio/x-realaudio',
                ),
                // SWF - audio/video - ShockWave Flash
                'swf' => array(
                    'pattern' => '^(F|C)WS',
                    'group' => 'audio-video',
                    'module' => 'swf',
                    'mime_type' => 'application/x-shockwave-flash',
                ),
                // TS - audio/video - MPEG-2 Transport Stream
                'ts' => array(
                    'pattern' => '^(\x47.{187}){10,}', // packets are 188 bytes long and start with 0x47 "G".  Check for at least 10 packets matching this pattern
                    'group' => 'audio-video',
                    'module' => 'ts',
                    'mime_type' => 'video/MP2T',
                ),
                // Still-Image formats
                // BMP  - still image - Bitmap (Windows, OS/2; uncompressed, RLE8, RLE4)
                'bmp' => array(
                    'pattern' => '^BM',
                    'group' => 'graphic',
                    'module' => 'bmp',
                    'mime_type' => 'image/bmp',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // GIF  - still image - Graphics Interchange Format
                'gif' => array(
                    'pattern' => '^GIF',
                    'group' => 'graphic',
                    'module' => 'gif',
                    'mime_type' => 'image/gif',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // JPEG - still image - Joint Photographic Experts Group (JPEG)
                'jpg' => array(
                    'pattern' => '^\xFF\xD8\xFF',
                    'group' => 'graphic',
                    'module' => 'jpg',
                    'mime_type' => 'image/jpeg',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // PCD  - still image - Kodak Photo CD
                'pcd' => array(
                    'pattern' => '^.{2048}PCD_IPI\x00',
                    'group' => 'graphic',
                    'module' => 'pcd',
                    'mime_type' => 'image/x-photo-cd',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // PNG  - still image - Portable Network Graphics (PNG)
                'png' => array(
                    'pattern' => '^\x89\x50\x4E\x47\x0D\x0A\x1A\x0A',
                    'group' => 'graphic',
                    'module' => 'png',
                    'mime_type' => 'image/png',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // SVG  - still image - Scalable Vector Graphics (SVG)
                'svg' => array(
                    'pattern' => '(<!DOCTYPE svg PUBLIC |xmlns="http:\/\/www\.w3\.org\/2000\/svg")',
                    'group' => 'graphic',
                    'module' => 'svg',
                    'mime_type' => 'image/svg+xml',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // TIFF - still image - Tagged Information File Format (TIFF)
                'tiff' => array(
                    'pattern' => '^(II\x2A\x00|MM\x00\x2A)',
                    'group' => 'graphic',
                    'module' => 'tiff',
                    'mime_type' => 'image/tiff',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // EFAX - still image - eFax (TIFF derivative)
                'efax' => array(
                    'pattern' => '^\xDC\xFE',
                    'group' => 'graphic',
                    'module' => 'efax',
                    'mime_type' => 'image/efax',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // Data formats
                // ISO  - data        - International Standards Organization (ISO) CD-ROM Image
                'iso' => array(
                    'pattern' => '^.{32769}CD001',
                    'group' => 'misc',
                    'module' => 'iso',
                    'mime_type' => 'application/octet-stream',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                    'iconv_req' => false,
                ),
                // RAR  - data        - RAR compressed data
                'rar' => array(
                    'pattern' => '^Rar\!',
                    'group' => 'archive',
                    'module' => 'rar',
                    'mime_type' => 'application/octet-stream',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // SZIP - audio/data  - SZIP compressed data
                'szip' => array(
                    'pattern' => '^SZ\x0A\x04',
                    'group' => 'archive',
                    'module' => 'szip',
                    'mime_type' => 'application/octet-stream',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // TAR  - data        - TAR compressed data
                'tar' => array(
                    'pattern' => '^.{100}[0-9\x20]{7}\x00[0-9\x20]{7}\x00[0-9\x20]{7}\x00[0-9\x20\x00]{12}[0-9\x20\x00]{12}',
                    'group' => 'archive',
                    'module' => 'tar',
                    'mime_type' => 'application/x-tar',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // GZIP  - data        - GZIP compressed data
                'gz' => array(
                    'pattern' => '^\x1F\x8B\x08',
                    'group' => 'archive',
                    'module' => 'gzip',
                    'mime_type' => 'application/x-gzip',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // ZIP  - data         - ZIP compressed data
                'zip' => array(
                    'pattern' => '^PK\x03\x04',
                    'group' => 'archive',
                    'module' => 'zip',
                    'mime_type' => 'application/zip',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // Misc other formats
                // PAR2 - data        - Parity Volume Set Specification 2.0
                'par2' => array(
                    'pattern' => '^PAR2\x00PKT',
                    'group' => 'misc',
                    'module' => 'par2',
                    'mime_type' => 'application/octet-stream',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // PDF  - data        - Portable Document Format
                'pdf' => array(
                    'pattern' => '^\x25PDF',
                    'group' => 'misc',
                    'module' => 'pdf',
                    'mime_type' => 'application/pdf',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // MSOFFICE  - data   - ZIP compressed data
                'msoffice' => array(
                    'pattern' => '^\xD0\xCF\x11\xE0\xA1\xB1\x1A\xE1', // D0CF11E == DOCFILE == Microsoft Office Document
                    'group' => 'misc',
                    'module' => 'msoffice',
                    'mime_type' => 'application/octet-stream',
                    'fail_id3' => 'ERROR',
                    'fail_ape' => 'ERROR',
                ),
                // CUE  - data       - CUEsheet (index to single-file disc images)
                'cue' => array(
                    'pattern' => '', // empty pattern means cannot be automatically detected, will fall through all other formats and match based on filename and very basic file contents
                    'group' => 'misc',
                    'module' => 'cue',
                    'mime_type' => 'application/octet-stream',
                ),
            );
        }
        return Utils::$format_info;
    }

    public static function _mime_content_type($filename)
    {
        $realpath = realpath($filename);
        if ($realpath && function_exists('finfo_file') && function_exists('finfo_open') && defined('FILEINFO_MIME_TYPE')
        ) {
            // Use the Fileinfo PECL extension (PHP 5.3+)
            return finfo_file(finfo_open(FILEINFO_MIME_TYPE), $realpath);
        }
        if (function_exists('mime_content_type')) {
            // Deprecated in PHP 5.3
            return mime_content_type($realpath);
        }
        $getFileFormatArray = Utils::getFileFormatArray();
        $path_parts = pathinfo($filename);
        $extension = strtolower($path_parts['extension']);
        $info = $getFileFormatArray[$extension];
        return $info['mime_type'];
    }

    public static function checkFileExist($filePath)
    {
        try {
            if (file_exists($filePath)) {
                return TRUE;
            }
        } catch (Exception $ex) {
            
        }
        return FALSE;
    }

    public static function checkCreateFolder($folder)
    {
        try {
            if (!Utils::checkFileExist($folder)) {
                if (mkdir($folder)) {
                    return TRUE;
                }
            } else {
                return TRUE;
            }
        } catch (Exception $ex) {
            
        }
        return FALSE;
    }
}
