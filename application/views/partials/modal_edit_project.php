<div class="modal fade" id="editProjectModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Project</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editProjectForm" method="post" action="">
        <div class="modal-body">
          <div class="row g-3">
            <div class="col-12">
              <label class="form-label">Title</label>
              <input name="title" id="edit_title" class="form-control" required>
            </div>
            <div class="col-12">
              <label class="form-label">Description</label>
              <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
            </div>
            <div class="col-md-6">
              <label class="form-label">Image URL</label>
              <input name="image" id="edit_image" class="form-control">
            </div>
            <div class="col-md-6">
              <label class="form-label">Link URL</label>
              <input name="url" id="edit_url" class="form-control">
            </div>
            <div class="col-12">
              <label class="form-label">Framework/Language</label>
              <select name="type[]" id="edit_type" class="form-control" multiple size="6">
                <option value="" disabled>-- Select framework / language --</option>
                <option value="php">PHP</option>
                <option value="javascript">JavaScript</option>
                <option value="html_css">HTML/CSS</option>
                <option value="nodejs">Node.js</option>
                <option value="react">React</option>
                <option value="vue">Vue.js</option>
                <option value="angular">Angular</option>
                <option value="uiux">UI/UX</option>
                <option value="cli">CLI / Tools</option>
                <option value="devops">DevOps</option>
              </select>
              <div class="form-text">Choose one or more frameworks or languages (hold Ctrl / Cmd to multi-select).</div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
          <button type="submit" class="btn-pill btn-primary-custom">Save</button>
        </div>
      </form>
    </div>
  </div>
</div>
