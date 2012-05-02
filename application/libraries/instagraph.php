<?php
/**
 * Instagram filters with PHP and ImageMagick
 *
 * @package    Instagraph
 * @url        http://instagraph.me (hosted by http://leftor.com)
 * @author     Webarto <dejan.marjanovic@gmail.com>
 * @copyright  NetTuts+
 * @license    http://creativecommons.org/licenses/by-nc/3.0/ CC BY-NC
 */
class Instagraph
{

    public $_image = NULL;
    public $_output = NULL;
    public $_prefix = 'IMG';
    private $_width = NULL;
    private $_height = NULL;
    private $_tmp = NULL;

    public static function factory($image, $output)
    {
        return new Instagraph($image, $output);
    }

    public function __construct($image = NULL, $output = NULL, $temp_path = NULL, $template_path = NULL) {
        if($image && $output && $temp_path) {
            $this->init($image, $output, $temp_path, $template_path);
        }
    }

    public function init($image, $output, $temp_path, $template_path)
    {
        if(file_exists($image))
        {
            $this->_image = $image;
            list($this->_width, $this->_height) = getimagesize($image);
            $this->_output = $output;
            $this->_temp_path = $temp_path;
            $this->_template_path = $template_path;
        }
        else
        {
            throw new Exception('File not found. Aborting.');
        }
    }

    public function tempfile()
    {
        # copy original file and assign temporary name
        $this->_tmp = $this->_temp_path.$this->_prefix.rand().'.jpg';
        copy($this->_image, $this->_tmp);
    }

    public function output()
    {
        # rename working temporary file to output filename
        $this->watermark($this->_tmp, $this->_template_path.'watermark.png');
        rename($this->_tmp, $this->_output);
    }

    public function execute($command)
    {
        # remove newlines and convert single quotes to double to prevent errors
        $command = str_replace(array("\n", "'"), array('', '"'), $command);
        # replace multiple spaces with one
        $command = preg_replace('#(\s){2,}#is', ' ', $command);
        # escape shell metacharacters
        $command = escapeshellcmd($command);
        # execute convert program
        exec($command);
    }
    
    /** ACTIONS */
    
    public function colortone($input, $color, $level, $type = 0)
    {
        $args[0] = $level;
        $args[1] = 100 - $level;
        $negate = $type == 0? '-negate': '';

        $this->execute("convert 
        {$input} 
        ( -clone 0 -fill $color -colorize 100% ) 
        ( -clone 0 -colorspace gray $negate ) 
        -compose blend -define compose:args=$args[0],$args[1] -composite 
        {$input}");
    }

    public function border($input, $color = 'black', $width = 20)
    {
        $this->execute("convert $input -bordercolor $color -border {$width}x{$width} $input");
    }

    public function frame($input, $frame)
    {
        $frame = $this->_template_path . $frame;
        $this->execute("convert $input ( $frame -resize {$this->_width}x{$this->_height}! -unsharp 1.5×1.0+1.5+0.02 ) -flatten $input");
    }

    public function watermark($input, $overlay, $position = 'SouthEast')
    {
        if(!file_exists($overlay)) return false;
        $this->execute("convert $input $overlay -gravity $position -composite $input");
    }
    
    public function vignette($input, $color_1 = 'none', $color_2 = 'black', $crop_factor = 1.5)
    {
        $crop_x = floor($this->_width * $crop_factor);
        $crop_y = floor($this->_height * $crop_factor);
        
        $this->execute("convert 
        ( {$input} ) 
        ( -size {$crop_x}x{$crop_y} 
        radial-gradient:$color_1-$color_2 
        -gravity center -crop {$this->_width}x{$this->_height}+0+0 +repage )
        -compose multiply -flatten 
        {$input}");   
    }
    
    /** FILTER METHODS */
    
    # GOTHAM
    public function filter_gotham()
    {
        $this->tempfile();
        $this->execute("convert $this->_tmp -modulate 120,10,100 -fill #222b6d -colorize 20 -gamma 0.5 -contrast -contrast $this->_tmp");
        $this->border($this->_tmp);
        $this->output();
    }

    # TOASTER
    public function filter_toaster()
    {
        $this->tempfile();
        $this->colortone($this->_tmp, '#330000', 100, 0);
        
        $this->execute("convert $this->_tmp -modulate 150,80,100 -gamma 1.2 -contrast -contrast $this->_tmp");
        
        $this->vignette($this->_tmp, 'none', 'LavenderBlush3');
        $this->vignette($this->_tmp, '#ff9966', 'none');
        
        $this->output();        
    }
    
    # NASHVILLE
    public function filter_nashville()
    {
        $this->tempfile();
        
        $this->colortone($this->_tmp, '#222b6d', 100, 0);
        $this->colortone($this->_tmp, '#f7daae', 100, 1);
        
        $this->execute("convert $this->_tmp -contrast -modulate 100,150,100 -auto-gamma $this->_tmp");
        $this->frame($this->_tmp, __FUNCTION__);
        
        $this->output();
    }
        
    # LOMO-FI
    public function filter_lomo()
    {
        $this->tempfile();
        
        $command = "convert $this->_tmp -channel R -level 30% -channel G -level 15% $this->_tmp";
        
        $this->execute($command);
        $this->vignette($this->_tmp);
        
        $this->output();
    }

    # KELVIN
    public function filter_kelvin()
    {
        $this->tempfile();
        
        $this->execute("convert 
        ( $this->_tmp -auto-gamma -modulate 120,50,100 ) 
        ( -size {$this->_width}x{$this->_height} -fill rgba(255,153,0,0.3) -draw 'rectangle 0,0 {$this->_width},{$this->_height}' ) 
        -compose multiply 
        $this->_tmp");
        $this->frame($this->_tmp, __FUNCTION__);

        $this->output();
    }

    # TILT SHIFT
    public function filter_tilt_shift()
    {
        $this->tempfile();

        $this->execute("convert 
        ( $this->_tmp -gamma 0.75 -modulate 100,130 -contrast ) 
        ( +clone -sparse-color Barycentric '0,0 black 0,%h white' -function polynomial 4,-4,1 -level 0,50% ) 
        -compose blur -set option:compose:args 5 -composite 
        $this->_tmp");

        $this->output();
    }

    # Charcoal
    public function filter_charcoal()
    {
        $this->tempfile();

        $this->execute("convert $this->_tmp "."
        -charcoal 5
        "." $this->_tmp");

        $this->output();
    }

    # Sketch
    public function filter_sketch()
    {
        $this->tempfile();

        $this->execute("convert $this->_tmp "."
        -colorspace gray -sketch 2x20+120
        "." $this->_tmp");

        $this->output();
    }

    # Whitening 
    public function filter_whitening()
    {
        $this->tempfile();

        $this->execute("convert $this->_tmp "."
        -sigmoidal-contrast 3x10%
        "." $this->_tmp");

        $this->output();
    }

    # Lomo 2
    public function filter_lomo2()
    {
        $this->tempfile();

        $this->execute("convert $this->_tmp "."
        -contrast 120 -modulate 100,200
        "." $this->_tmp");
        $this->vignette($this->_tmp, 'none', 'black', 1.3);
        $this->output();
    }

    # for test
    public function filter_test()
    {
        $this->tempfile();

        $this->execute("convert $this->_tmp "."
        ....
        "." $this->_tmp");

        $this->vignette($this->_tmp);

        $this->output();
    }

    public function random() {
        $class_methods = get_class_methods($this);
        $filters = array();
        foreach ($class_methods as $method) {
            if(preg_match("/^(filter_)/", $method)){
                $filters[] = $method;
            }
        }
        $n = array_rand($filters);
        $this->{$filters[$n]}();
    }
}
