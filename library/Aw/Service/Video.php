<?php
class Aw_Service_Video extends Aw_Service_ServiceAbstract {

	private $_ini;
    const THUMBNAIL_WIDTH = '120';
    const THUMBNAIL_HEIGHT = '68';
    const THUMBNAIL_QUANTITIY = 5;
    const THUMBNAIL_INTERVAL = 5;

    public function init() {
        $ini = Zend_Registry::get("Zend_Application");
        $this->_ini = $ini->getOptions();
        if(!isset($this->_ini['settings']['video']['ffmpeg']['alias'])) {
            throw new Exception('Please provide the appropriate settings for ffmpeg

            settings.video.ffmpeg.alias
            settings.video.ffmpeg.imageLibrary
            settings.video.ffmpeg.acodec
            settings.video.ffmpeg.vcodec
            settings.video.ffmpeg.aspect
            settings.video.ffmpeg.bitrateVariable
            ');
        }
    }

    public function findVideoDuration($videoPath)
    {
        $duration = shell_exec(sprintf(
            '"%s" -i %s 2>&1',
            $this->_ini['settings']['video']['ffmpeg']['alias'],
            $videoPath
         ));
        $search = '/Duration: (.*?),/';
        $duration = preg_match($search, $duration, $matches, PREG_OFFSET_CAPTURE, 3);
        return trim($matches[1][0]);
    }

    public function findVideoBitRate($videoPath)
    {
        $cmd = sprintf(
            '"%s" -i %s 2>&1',
            $this->_ini['settings']['video']['ffmpeg']['alias'],
            $videoPath
        );
        $bitrate = shell_exec($cmd);
        $search = '/bitrate: (.*?)kb/';
        $bitrate = preg_match($search, $bitrate, $matches, PREG_OFFSET_CAPTURE, 3);
        return trim($matches[1][0]);
    }

    /**
     *
     * Take a screenshot from a video file at a set duration point.
     * @param string $videoPath
     * @param integer $duration
     * @param string $savePath
     * @param string $format
     */
    public function takeScreenshot($videoPath, $duration, $savePath, $format)
    {
        exec(sprintf(
            '"%s" -y -loglevel quiet -i %s -f %s -ss %d -s %s -vframes 1 "%s/%s.%s"',
            $this->_ini['settings']['video']['ffmpeg']['alias'],
            $videoPath,
            $this->_ini['settings']['video']['ffmpeg']['imageLibrary'],
            $duration,
            self::THUMBNAIL_WIDTH . 'x' . self::THUMBNAIL_HEIGHT,
            $savePath,
            $duration,
            $format
        ));
        return sprintf("%s/%s.%s",$savePath,$duration,$format);
    }

    public function transcodeVideo($videoPath, array $params)
    {
        if(!array_key_exists('videoBitRate', $params) && !array_key_exists('output', $params))
           throw new Exception('Missing required parameters. Can\'t transcode correctly');

        exec(
           sprintf(
                "%s -y -loglevel quiet -i %s -acodec %s -vcodec %s -aspect %s %s %sk %s",
                $this->_ini['settings']['video']['ffmpeg']['alias'],
                $videoPath,
                $this->_ini['settings']['video']['ffmpeg']['acodec'],
                $this->_ini['settings']['video']['ffmpeg']['vcodec'],
                $this->_ini['settings']['video']['ffmpeg']['aspect'],
                $this->_ini['settings']['video']['ffmpeg']['bitrateVariable'],
                $params['videoBitRate'],
                $params['output']
           )
        );
        return $this;
    }
}