<% if $VisibleCategories %>
  <div class="categories">
    <% loop $VisibleCategories %>
      <article class="category">
        <header>
          <h3>$Title</h3>
        </header>
        <div class="projects">
          $Projects
        </div>
      </article>
    <% end_loop %>
  </div>
<% else %>
  <% include Alert Type='warning', Text=$NoDataMessage %>
<% end_if %>
