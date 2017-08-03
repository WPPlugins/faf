<?php
class faf_remove_html extends fafFilter 
 {
 
      public static $name = "remove_html"; 
     // public static $description = "Remove HTML from post"; 
      public static $context = "posts";
      //c_search_title, $c_search_excerpt, $c_search_content, 

    protected static $controls = "search_title, search_excerpt, search_content, html_allow_styles, html_allow_images,html_allow_links,html_allow_custom"; 

	public static function display_help_text()
	{
		echo "<p>";
		_e("Will remove HTML from post, title and excerpt. You can choose to keep certain families of tags.");
		echo "</p>";
		 echo "<p>";
		_e("Keep HTML styles will not remove markup like strong, em, h1,h2 etc");
		echo "</p>";
		echo "<p>"; 
		_e("Custom tags: you can type the name of the tags you like preserved ( e.g. div ). You can enter multiple keywords comma-seperated
		"); 
		echo "</p>";	
	}
	
	/* Function not an attribute due for gettext */ 
	public static function get_description()
	{
		return __("Remove HTML from post"); 
	
	}
           
        function execute() 
        {
  		$post = $this->post; 
 		$args = $this->args; 
		$content = $post["post_content"]; 
		$excerpt = $post["post_excerpt"];
		$title = $post["post_title"];

		$a_style = array("b" => array(),
				"em" => array(),
				"i" => array(),
				"strong" => array(),
				"u" => array(),
				"del" => array() ); 
		$a_img = array("img" => array(
					"src" => array(),
					"width" => array(),
					"height" => array(),
					"title" => array(),
					"alt" => array()) );
		$a_link = array("a" => array("href" => array(),
					"target" => array()) ); 
		
		
		$allowed_array = array();  // see what is allowed through 
		if (isset($args["filter_allow_styles"]) && $args["filter_allow_styles"] == 1) 
			$allowed_array = array_merge($allowed_array,$a_style);
		if (isset($args["filter_allow_images"]) && $args["filter_allow_images"] == 1) 
			$allowed_array = array_merge($allowed_array,$a_img);
		if (isset($args["filter_allow_links"]) && $args["filter_allow_links"] == 1) 
			$allowed_array = array_merge($allowed_array,$a_link);
		if (isset($args["filter_allow_custom"])) 
		{
			$custom = explode(",",$args["filter_allow_custom"]); 
			$custom_array = array();
			foreach ($custom as $c)
			{
				$custom_array[$c] = array(); 	
			
			}
			$allowed_array = array_merge($allowed_array,$custom_array);
		}
		
		// check for multiple keywords.
		if (isset($args["filter_search_title"]) && $args["filter_search_title"] == 1)
			$title = wp_kses($title, $allowed_array);

		if (isset($args["filter_search_content"]) && $args["filter_search_content"] == 1)
			$content = wp_kses($content, $allowed_array);

		if (isset($args["filter_search_excerpt"]) && $args["filter_search_excerpt"] == 1)     	
		      	$excerpt = wp_kses($excerpt,$allowed_array); 
	       
		$post["post_content"] = $content;
		$post["post_excerpt"] = $excerpt;
		$post["post_title"] = $title;  
	       
		
	      return $post;
     	    }
 
	   
     protected static function setControls()
     {
     	$controls = parent::setControls();
	$controls["html_allow_styles"] = array(
		"name" => "filter_allow_styles",
		"desc" => __("Keep HTML-Styles and Headings"), 			
		"type" => "checkbox");
	$controls["html_allow_images"] = array(
		"name" => "filter_allow_images",
		"desc" => __("Keep Images"), 
		"type" => "checkbox");
	$controls["html_allow_links"] = array(
		"name" => "filter_allow_links",
		"desc" => __("Keep Links"), 
		"type" => "checkbox");				
	$controls["html_allow_custom"] = array(
		"name" => "filter_allow_custom",
		"desc" => __("Keep custom tags:"), 
		"type" => "text");		
	return $controls;	
     }  
}
?>
