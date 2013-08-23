<?php
class Ecm_Post
{
	const TYPE_POST			= 'POST';
	const TYPE_THINK		= 'THINK';
	const TYPE_QUESTION		= 'QUESTION';
	
	protected $id = NULL;
	protected $authorId = NULL;
	protected $author = NULL;
	protected $type = NULL;
	protected $slug;
	protected $title;
	protected $content;
	protected $dateInsert;
	protected $dateUpdate;
	protected $datePublication;
	protected $categories;
	
	public function __construct ()
	{
		$this->categories = array();
	}
	
	public static function constructFromDb ($obj)
	{
		if ($obj)
		{
			$post = new self();
			$post->id = $obj->id;
			$post->authorId = $obj->author;
			$post->type = $obj->type;
			$post->slug = $obj->slug;
			$post->title = $obj->title;
			$post->content = $obj->content;
			$post->dateInsert = $obj->date_insert;
			$post->dateUpdate = $obj->date_update;
			$post->datePublication = $obj->date_publication;
		}
		else
			$post = NULL;
		
		return $post;
	}
	
	public static function getTypes ()
	{
		return array
		(
			self::TYPE_POST		=> array
			(
				'label'			=> 'Article',
				'description'	=> "Une soumission de type article est un texte développé traitant d'un sujet particulier.",
				'default'		=>	TRUE
			),
			self::TYPE_THINK	=> array
			(
				'label'			=> 'Pensée',
				'description'	=> "Une soumission de type pensée est l'expression d'une idée ou d'un sentiment. La pensée s'apparente aux courtes interventions que l'on retrouve notamment sur les réseaux sociaux."
			),
			self::TYPE_QUESTION	=> array
			(
				'label'			=> 'Question/Réponses',
				'description'	=> "Une soumission de type question est une interrogation à laquelle on attend une réponse via les commentaires."
			)
		);
	}
	
	public static function getDefaultType ()
	{
		$types = self::getTypes();
		$default = NULL;
		
		foreach ($types as $type => $data)
		{
			if (isset($data['default']) && $data['default'])
			{
				$default = $type;
				break;
			}
		}
		
		return $default;
	}
	
	public function save ()
	{
		global $Ecm_db;
		
		// Insertion/MAJ de l'article
		$slug = $Ecm_db->secureString($this->slug);
		$title = $Ecm_db->secureString($this->title);
		$content = $Ecm_db->secureString($this->content);
		
		if (isset($this->id))
		{
			$query = 	"Update posts Set type='" . $this->type . "', slug='$slug', title='$title', content='$content', date_update=Now() "
					.	"Where id=" . $this->id;
			
			$Ecm_db->query($query);
		}
		else
		{
			$query = "Insert Into posts (author, type, slug, date_insert, date_update, date_publication, title, content) Values (" . $this->authorId . ", '" . $this->type . "', $slug', Now(), Now(), Now(), '$title', '$content')";
			$Ecm_db->query($query);
			
			$this->id = $Ecm_db->lastInsertId();
		}
		
		$postQueryOk = $Ecm_db->affectedRows() == 1; 
		
		// Insertion/MAJ des catégories
		if (count($this->categories))
		{
			$query = "Delete From posts_categories Where id_post=" . $this->id;
			$Ecm_db->query($query);
			
			foreach ($this->categories as $category)
			{
				$query = "Insert Into posts_categories (id_post, id_category) Values (" . $this->id . ", " . $category->getId() . ")";
				$Ecm_db->query($query);
			}
		}
		
		return $postQueryOk;
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
	
	public function getAuthor ()
	{
		if (!$this->author)
		{
			Ecm_QueryAuthor::find(array('author-id' => $this->authorId));
			$this->author = Ecm_QueryAuthor::next();
		}
		
		return $this->author;
	}
	
	public function getType ()
	{
		return $this->type;
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
		return preg_replace('#(<\?(php)?|\?>|</?(script|style)[^>]+>)#', '', stripslashes($this->content));
	}
	
	public function getDateInsert ($format = NULL)
	{
		return $format ? date($format, strtotime($this->dateInsert)) : $this->dateInsert;
	}
	
	public function getDateUpdate ($format = NULL)
	{
		return $format ? date($format, strtotime($this->dateUpdate)) : $this->dateUpdate;
	}
	
	public function getDatePublication ($format = NULL)
	{
		return $format ? date($format, strtotime($this->datePublication)) : $this->datePublication;
	}
	
	public function getPermalink ()
	{
		global $permaStructurePosts;
		$url = $permaStructurePosts;
		
		if (preg_match_all('#:([^:]+):#', $url, $tab))
		{
			for ($i = 0; $i < count($tab[1]); $i++)
			{
				switch ($tab[1][$i])
				{
					case Ecm_Urls::PLR_POST_TITLE :
						$replacement = $this->getSlug();
					break;
					
					case Ecm_Urls::PLR_CATEGORY_TITLE :
						$replacement = 'category';
						$categories = $this->getCategories();
						
						if ($categories)
						{
							Ecm_QueryCategories::find(array('category-slug' => $categories[0]->getSlug()));
							$category = Ecm_QueryCategories::next();
							
							if ($category)
								$replacement = $category->getSlug();
						}
					break;
					
					default :
						$replacement = $tab[1][$i];
				}
				
				$url = str_replace(':' . $tab[1][$i] . ':', $replacement, $url);
			}
		}
		
		return $url;
	}
	
	public function getCategories ($indexedFromZero = TRUE)
	{
		// Les catégories n'ont pas encore été récupérées en base, on va les chercher
		if (!count($this->categories))
		{
			global $Ecm_db;
			
			$query = "Select a.* From categories a, posts b, posts_categories c Where a.id=c.id_category And b.id=c.id_post";
			$result = $Ecm_db->query($query);
			
			while ($response = $Ecm_db->fetch($result))
			{
				$category = Ecm_Category::constructFromDb($response);
				$this->categories[$category->getId()] = $category;
			}
		}
		
		return $indexedFromZero ? array_values($this->categories) : $this->categories;
	}
	
	public function containsCategory ($categoryId)
	{
		return key_exists($categoryId, $this->getCategories(FALSE));
	}
	
	
	//--> SETTERS
	
	public function setAuthorId ($n)
	{
		$this->authorId = $n;
	}
	
	public function setType ($s)
	{
		if (array_key_exists($s, self::getTypes()))
			$this->type = $s;
		else
			$this->type = self::getDefaultType();
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
	
	public function clearCategories ()
	{
		$this->categories = array();
	}
	
	public function addCategory ($c)
	{
		if (!($c instanceof Ecm_Category))
		{
			Ecm_QueryCategories::find(array('category-id' => $c));
			$c = Ecm_QueryCategories::next();
		}
		
		if ($c)
			$this->categories[$c->getId()] = $c;
	}
}