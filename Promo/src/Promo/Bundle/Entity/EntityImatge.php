<?php
namespace Promo\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="imatges")
 * 
 * @author alex	
 *
 */
class EntityImatge {
	
	/**
	 * @ORM\Id
	 * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;	
	
	/**
	 * @ORM\Column(type="string", length=50)
	 */
	protected $path;

	/**
	 * @ORM\Column(type="string", length=50, nullable=true)
	 */
	protected $titol;
	
	/**
	 * @Assert\File(maxSize="2000000")
	 */
	protected $file;
	
	public function __toString() {
		return (string) $this->$path;
	}

	public function getWidth() {
		$image_info = getimagesize($this->getAbsolutePath());
		return $image_info[0];
	}
	
	public function getHeight() {
		$image_info = getimagesize($this->getAbsolutePath());
		return $image_info[1];
	}

	public function upload($name = null)
	{
		// the file property can be empty if the field is not required
		if (null === $this->getFile()) {
			return;
		}
	
		// use the original file name here but you should
		// sanitize it at least to avoid any security issues
	
		// move takes the target directory and then the
		// target filename to move to
		$extension = $this->getFile()->guessExtension();
		
		if ($extension == null or ($extension != "png" and $extension != "jpg" 
								and $extension != "jpeg" and $extension != "gif")) {
			return false;
		} 

		// 	set the path property to the filename where you've saved the file
		if ($name == null) {
			$this->path = time() . "_". $this->netejarPath($this->getFile()->getClientOriginalName());
		} else {
			$this->path = time() . "_". $this->netejarPath($name) . "." . $extension;
		}

		$this->getFile()->move($this->getUploadRootDir(), $this->path);

		// clean up the file property as you won't need it anymore
		$this->file = null;
			
		return true;
	}

	public function getAbsolutePath()
	{
		return null === $this->path
		? null
		: $this->getUploadRootDir().'/'.$this->path;
	}
	
	public function getWebPath()
	{
		return null === $this->path
		? null
		: $this->getUploadDir().'/'.$this->path;
	}
	
	protected function getUploadRootDir()
	{
		// the absolute directory path where uploaded
		// documents should be saved
		return __DIR__.'/../../../../web/'.$this->getUploadDir();
	}
	
	protected function getUploadDir()
	{
		// get rid of the __DIR__ so it doesn't screw up
		// when displaying uploaded doc/image in the view.
		return 'images/items';
	}
	
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return EntityImatge
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set titol
     *
     * @param string $titol
     * @return EntityImatge
     */
    public function setTitol($titol)
    {
        $this->titol = $titol;
    
        return $this;
    }

    /**
     * Get titol
     *
     * @return string 
     */
    public function getTitol()
    {
        return $this->titol;
    }
    
    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
    	$this->file = $file;
    }
    
    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
    	return $this->file;
    }
    
    function netejarPath($string)
    {
    
    	$string = trim($string);
    
    	$string = str_replace(
    			array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
    			array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
    			$string
    	);
    
    	$string = str_replace(
    			array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
    			array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
    			$string
    	);
    
    	$string = str_replace(
    			array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
    			array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
    			$string
    	);
    
    	$string = str_replace(
    			array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
    			array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
    			$string
    	);
    
    	$string = str_replace(
    			array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
    			array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
    			$string
    	);
    
    	$string = str_replace(
    			array('ñ', 'Ñ', 'ç', 'Ç'),
    			array('n', 'N', 'c', 'C',),
    			$string
    	);
    
    	//Esta parte se encarga de eliminar cualquier caracter extraño
    	$string = str_replace(
    			array("\\", "¨", "º", "-", "~",
    					"#", "@", "|", "!", "\"",
    					"·", "$", "%", "&", "/",
    					"(", ")", "?", "'", "¡",
    					"¿", "[", "^", "`", "]",
    					"+", "}", "{", "¨", "´",
    					">", "< ", ";", ",", ":",
    					".", " "),
    			'_',
    			$string
    	);
    
    
    	return $string;
    }
}