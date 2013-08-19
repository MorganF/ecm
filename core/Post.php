<?php
class Ecm_Post
{
	protected $id = NULL;
	protected $authorId = NULL;
	protected $slug;
	protected $title;
	protected $content;
	
	public static function constructFromDb ($obj)
	{
		if ($obj)
		{
			$post = new self();
			$post->id = $obj->id;
			$post->authorId = $obj->author;
			$post->title = $obj->title;
			$post->content = $obj->content;
		}
		else
			$post = NULL;
		
		return $post;
	}
	
	public function save ()
	{
		global $Ecm_db;
		
		$slug = $Ecm_db->secureString($this->slug);
		$title = $Ecm_db->secureString($this->title);
		$content = $Ecm_db->secureString($this->content);
		
		if (isset($this->id))
		{
			$query = 	"Update posts Set slug='$slug', title='$title', content='$content' "
					.	"Where id=" . $this->id;
			
			$Ecm_db->query($query);
		}
		else
		{
			$query = "Insert Into posts (author, slug, date_insert, date_update, date_publication, title, content) Values (" . $this->authorId . ", '$slug', Now(), Now(), Now(), '$title', '$content')";
			$Ecm_db->query($query);
			
			$this->id = $Ecm_db->lastInsertId();
		}
		
		return $Ecm_db->affectedRows() == 1;
	}
	
	
	//--> GETTERS
	
	public function getId ()
	{
		return $this->id;
	}
	
	public function getAuthorId ()
	{
		return $this->authorId;
	}
	
	public function getSlug ()
	{
		return stripslashes($this->slug);
	}
	
	public function getTitle ()
	{
		return stripslashes($this->title);
	}
	
	public function getContent ()
	{
		return htmlspecialchars(stripslashes($this->content), ENT_COMPAT|ENT_SUBSTITUTE, "UTF-8");
	}
	
	
	//--> SETTERS
	
	public function setAuthorId ($n)
	{
		$this->authorId = $n;
	}
	
	public function setSlug ($s)
	{
		$this->slug = $s;
	}
	
	public function setTitle ($s)
	{
		$this->title = $s;
	}
	
	public function setContent ($s)
	{
		$this->content = $s;
	}
}