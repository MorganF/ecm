var isNewPost = !$('#postIdHidden').val().length;
var postForm = $('#postForm');
var postTitleInput = $('#postTitleInput');
var postSlugInput = $('#postSlugInput');
var postSlugValue = null;
var postSlugAuto = isNewPost;

$(document).ready(function()
{
	// Initialisation des valeurs par défaut pour les nouveaux posts
	if (isNewPost)
	{
		postTitleInput.val('Titre de votre article');
		setSlug(slugItUnique(postTitleInput.val()));
	}
	
	// Lorsque le titre est modifié, il faut modifier le slug
	postTitleInput.blur(function()
	{
		if (postSlugAuto)
			setSlug(slugItUnique(postTitleInput.val()));
	});
	
	// Modification manuelle du slug
	postSlugInput.blur(function()
	{
		var slug = postSlugInput.val();
		
		// Demande du slug par défaut lorsque l'on vide le champs
		if (!slug.length)
		{
			setSlug(slugItUnique(postTitleInput.val()));
			postSlugAuto = true;
		}
		else
		{
			// Le slug a été modifié manuellement
			if (postSlugValue != postSlugInput.val())
			{
				setSlug(slugItUnique(postSlugInput.val()));
				postSlugAuto = false;
			}
		}
	});
	
	// Modification du type d'article
	$('#radiosPostTypes input').each(function()
	{
		$(this).click(function()
		{
			$('#postTypeHidden').val($(this).val());
		});
	});
	
	// Sélection des catégories
	postForm.append('<div id="postCategoriesInputs" style="display:none;"></div>');
	
	$('#checkboxesPostCategories input').each(function()
	{
		var ref = $(this);
		postCategoryCheckboxAction(ref);
		
		ref.click(function()
		{
			postCategoryCheckboxAction(ref);
		});
	});
});

function postCategoryCheckboxAction (ref)
{
	if (ref.is(':checked'))
		$('#postCategoriesInputs').append('<input type="checkbox" name="post-category[]" value="' + ref.val() + '" id="' + ref.attr('id') + '_hidden" checked="checked" />');
	else
		$('#' + ref.attr('id') + '_hidden').remove();
}

function slugItUnique (text, currentSlug)
{
	var result;
	
	$.ajax(
	{
		type:		'GET',
		async:		false,
		url: 		'cgi/slugItUnique.php?slug=' + text + '&currentSlug=' + $('#postSlugHidden').val(),
		success:	function(data)
		{
			result = data;
		}
	});
	
	return result;
}

function setSlug (slug)
{
	postSlugInput.val(slug);
	postSlugValue = slug;
}