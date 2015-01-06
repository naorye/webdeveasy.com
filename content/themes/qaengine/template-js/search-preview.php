<script type="text/template" id="search_preview_template">
	<% _.each(questions, function(question){ 
		avatar = (typeof(question.et_avatar) === "object") ? question.et_avatar.thumbnail : question.et_avatar;
		hightlight = question.post_title.replace( search_term, '<strong>' + search_term + "</strong>" );
	%>
	<div class="i-preview">
		<a href="<%= question.permalink %>">
			<div class="i-preview-content">
				<span class="i-preview-title"><%= hightlight %></span>
			</div>
		</a>
	</div>
	<% }); %>
	<div class="i-preview i-preview-showall">
		<% if ( total > 0 && pages > 1 ) { %>
		<a href="<%= search_link %>"><?php printf( __('View all %s results', ET_DOMAIN), '<%= total %>' ); ?></a>
		<% } else if ( pages == 1) { %>
		<a href="<%= search_link %>"><?php _e('View all results', ET_DOMAIN) ?></a>
		<% } else { %>
		<a> <?php _e('No results found', ET_DOMAIN) ?> </a>
		<% } %>
	</div>
</script>