<?php
// Partial: project_type_select.php
// Renders the Framework/Language multi-select used in project forms.
$typeOptions = [
  'php' => 'PHP',
  'javascript' => 'JS',
  'html_css' => 'HTML/CSS',
  'nodejs' => 'Node',
  'react' => 'React',
  'vue' => 'Vue',
  'angular' => 'Angular',
  'sql' => 'SQL',
  'mysql' => 'MySQL',
  'uiux' => 'UI',
  'cli' => 'CLI',
  'devops' => 'DevOps'
];

$selectedTypes = [];
if (isset($project) && isset($project['type'])) {
  if (is_array($project['type'])) $selectedTypes = $project['type'];
  else $selectedTypes = array_map('trim', explode(',', $project['type']));
}
?>
<select name="type[]" class="form-control" multiple size="6">
  <option value="" disabled>-- Select framework / language --</option>
  <?php foreach ($typeOptions as $val => $label): $sel = in_array($val, $selectedTypes) ? 'selected' : ''; ?>
    <option value="<?php echo htmlspecialchars($val); ?>" <?php echo $sel; ?>><?php echo htmlspecialchars($label); ?></option>
  <?php endforeach; ?>
</select>
<div class="form-text">Choose one or more frameworks or languages (hold Ctrl / Cmd to multi-select).</div>
