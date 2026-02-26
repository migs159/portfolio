<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,minimum-scale=1,user-scalable=no">
  <meta name="csrf-token" content="<?php echo $this->security->get_csrf_hash(); ?>">
  <meta name="csrf-token-name" content="<?php echo $this->security->get_csrf_token_name(); ?>">
  <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'CRUD Dashboard'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@600;700;800&display=swap" rel="stylesheet">
  <!-- using SweetAlert2 for toasts instead of iziToast -->
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/theme.css') : '/assets/css/theme.css'); ?>">
  <link rel="stylesheet" href="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/css/crud-dashboard-custom.css') : '/assets/css/crud-dashboard-custom.css'); ?>">
</head>
<body>
  <?php if (function_exists('get_instance')) {
    $ci = &get_instance();
    $ci->load->view('partials/header_crud');
  } else {
    if (isset($this) && method_exists($this->load, 'view')) {
      $this->load->view('partials/header_crud');
    }
  }
  ?>

  <!-- Top Header (redesigned as Event Management) -->
  <div class="page-header-top">
    <div class="container-main">
      <div class="page-header">
        <div class="header-content">
          <h1><i class="fas fa-cube me-2"></i>Admin Dashboard</h1>
          <p class="header-subtitle">Manage your portfolio content</p>
        </div>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-12 col-md-6 col-sm-12 d-none">
      <div class="alert alert-info text-center mb-4">
        <i class="fas fa-info-circle me-2"></i>
        Welcome to the Admin Dashboard. Manage your portfolio content here.
      </div>
    </div>
     <div class="col-lg-12 col-md-6 col-sm-12">
      <div class="alert alert-info text-center mb-4">
        <i class="fas fa-info-circle me-2"></i>
        Welcome to the Admin Dashboard. Manage your portfolio content here.
      </div>
    </div>
  </div>
  <!-- Main Content -->
  <div class="container-main main-content">

    <!-- HOME SECTION -->
    <div class="section-content section-content-hidden" id="home-section">
      <div class="col-12">
        <div class="table-section">
          <div class="table-section-wrapper">
            <div class="table-overflow-wrapper">
            <table class="table table-borderless">
              <thead>
                <tr>
                  <th>Home Titles</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="table-cell-muted"><strong>Profile Image:</strong> <span id="homeProfileImage"><?php echo file_exists(FCPATH . 'assets/img/profiles/profile.png') ? 'profile.png' : 'Not set'; ?></span></td>
                  <td>
                    <div class="action-buttons-flex">
                      <button class="btn btn-sm btn-outline-primary btn-view-field" data-field="profile_image" data-section="home" data-label="Profile Image" data-value="<?php echo base_url('assets/img/profiles/profile.png'); ?>" data-type="image" title="View"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary btn-edit-field" data-field="profile_image" data-section="home" data-label="Profile Image" data-value="" data-type="file" title="Edit"><i class="fas fa-edit"></i></button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="table-cell-muted"><strong>Profile Name:</strong> <span id="homeTitle"><?php echo isset($portfolio['hero_title']) ? htmlspecialchars($portfolio['hero_title']) : 'Miguel Andrei del Rosario'; ?></span></td>
                  <td>
                    <div class="action-buttons-flex">
                      <button class="btn btn-sm btn-outline-primary btn-view-field" data-field="hero_title" data-section="home" data-label="Profile Name" data-value="<?php echo isset($portfolio['hero_title']) ? htmlspecialchars($portfolio['hero_title']) : 'Miguel Andrei del Rosario'; ?>" title="View"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary btn-edit-field" data-field="hero_title" data-section="home" data-label="Profile Name" data-value="<?php echo isset($portfolio['hero_title']) ? htmlspecialchars($portfolio['hero_title']) : 'Miguel Andrei del Rosario'; ?>" data-type="text" title="Edit"><i class="fas fa-edit"></i></button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="table-cell-muted"><strong>Subtitle:</strong> <span id="homeSubtitle"><?php echo isset($portfolio['hero_subtitle']) ? htmlspecialchars($portfolio['hero_subtitle']) : 'A Web Developer Trainee'; ?></span></td>
                  <td>
                    <div class="action-buttons-flex">
                      <button class="btn btn-sm btn-outline-primary btn-view-field" data-field="hero_subtitle" data-section="home" data-label="Subtitle" data-value="<?php echo isset($portfolio['hero_subtitle']) ? htmlspecialchars($portfolio['hero_subtitle']) : 'A Web Developer Trainee'; ?>" title="View"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary btn-edit-field" data-field="hero_subtitle" data-section="home" data-label="Subtitle" data-value="<?php echo isset($portfolio['hero_subtitle']) ? htmlspecialchars($portfolio['hero_subtitle']) : 'A Web Developer Trainee'; ?>" data-type="text" title="Edit"><i class="fas fa-edit"></i></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ABOUT ME SECTION -->
    <div class="section-content section-content-hidden" id="about-section">
      <div class="table-section">
        <div class="table-section-wrapper">
          <div class="table-overflow-wrapper">
            <table class="table table-borderless">
              <thead>
                <tr>
                  <th>About Titles</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="table-cell-muted"><strong>About Content:</strong> <span id="aboutContent"><?php echo isset($portfolio['about_content']) ? htmlspecialchars(mb_substr($portfolio['about_content'], 0, 80)) . '...' : 'I\'m a motivated Information Technology student...'; ?></span></td>
                  <td>
                    <div class="action-buttons-flex">
                      <button class="btn btn-sm btn-outline-primary btn-view-field" data-field="about_content" data-section="about" data-label="About Content" data-value="<?php echo isset($portfolio['about_content']) ? htmlspecialchars($portfolio['about_content']) : ''; ?>" title="View"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary btn-edit-field" data-field="about_content" data-section="about" data-label="About Content" data-value="<?php echo isset($portfolio['about_content']) ? htmlspecialchars($portfolio['about_content']) : ''; ?>" data-type="textarea" title="Edit"><i class="fas fa-edit"></i></button>
                      <button class="btn btn-sm btn-outline-danger btn-clear-field" data-field="about_content" data-section="about" title="Delete"><i class="fas fa-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="table-cell-muted"><strong>Elementary:</strong> <span id="eduElementary"><?php echo isset($portfolio['education_elementary']) ? htmlspecialchars($portfolio['education_elementary']) : 'Not set'; ?></span></td>
                  <td>
                    <div class="action-buttons-flex">
                      <button class="btn btn-sm btn-outline-primary btn-view-field" data-field="education_elementary" data-section="about" data-label="Elementary School" data-value="<?php echo isset($portfolio['education_elementary']) ? htmlspecialchars($portfolio['education_elementary']) : ''; ?>" title="View"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary btn-edit-field" data-field="education_elementary" data-section="about" data-label="Elementary School" data-value="<?php echo isset($portfolio['education_elementary']) ? htmlspecialchars($portfolio['education_elementary']) : ''; ?>" data-type="text" title="Edit"><i class="fas fa-edit"></i></button>
                      <button class="btn btn-sm btn-outline-danger btn-clear-field" data-field="education_elementary" data-section="about" title="Delete"><i class="fas fa-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="table-cell-muted"><strong>High School:</strong> <span id="eduHighSchool"><?php echo isset($portfolio['education_high_school']) ? htmlspecialchars($portfolio['education_high_school']) : 'Not set'; ?></span></td>
                  <td>
                    <div class="action-buttons-flex">
                      <button class="btn btn-sm btn-outline-primary btn-view-field" data-field="education_high_school" data-section="about" data-label="High School" data-value="<?php echo isset($portfolio['education_high_school']) ? htmlspecialchars($portfolio['education_high_school']) : ''; ?>" title="View"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary btn-edit-field" data-field="education_high_school" data-section="about" data-label="High School" data-value="<?php echo isset($portfolio['education_high_school']) ? htmlspecialchars($portfolio['education_high_school']) : ''; ?>" data-type="text" title="Edit"><i class="fas fa-edit"></i></button>
                      <button class="btn btn-sm btn-outline-danger btn-clear-field" data-field="education_high_school" data-section="about" title="Delete"><i class="fas fa-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="table-cell-muted"><strong>Senior High School:</strong> <span id="eduSeniorHigh"><?php echo isset($portfolio['education_senior_high']) ? htmlspecialchars($portfolio['education_senior_high']) : 'Not set'; ?></span></td>
                  <td>
                    <div class="action-buttons-flex">
                      <button class="btn btn-sm btn-outline-primary btn-view-field" data-field="education_senior_high" data-section="about" data-label="Senior High School" data-value="<?php echo isset($portfolio['education_senior_high']) ? htmlspecialchars($portfolio['education_senior_high']) : ''; ?>" title="View"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary btn-edit-field" data-field="education_senior_high" data-section="about" data-label="Senior High School" data-value="<?php echo isset($portfolio['education_senior_high']) ? htmlspecialchars($portfolio['education_senior_high']) : ''; ?>" data-type="text" title="Edit"><i class="fas fa-edit"></i></button>
                      <button class="btn btn-sm btn-outline-danger btn-clear-field" data-field="education_senior_high" data-section="about" title="Delete"><i class="fas fa-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="table-cell-muted"><strong>College:</strong> <span id="eduCollege"><?php echo isset($portfolio['education_college']) ? htmlspecialchars($portfolio['education_college']) : 'Not set'; ?></span></td>
                  <td>
                    <div class="action-buttons-flex">
                      <button class="btn btn-sm btn-outline-primary btn-view-field" data-field="education_college" data-section="about" data-label="College / University" data-value="<?php echo isset($portfolio['education_college']) ? htmlspecialchars($portfolio['education_college']) : ''; ?>" title="View"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary btn-edit-field" data-field="education_college" data-section="about" data-label="College / University" data-value="<?php echo isset($portfolio['education_college']) ? htmlspecialchars($portfolio['education_college']) : ''; ?>" data-type="text" title="Edit"><i class="fas fa-edit"></i></button>
                      <button class="btn btn-sm btn-outline-danger btn-clear-field" data-field="education_college" data-section="about" title="Delete"><i class="fas fa-trash"></i></button>
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="table-cell-muted"><strong>Certification:</strong> <span id="eduCertification"><?php echo isset($portfolio['education_certification']) ? htmlspecialchars($portfolio['education_certification']) : 'Not set'; ?></span></td>
                  <td>
                    <div class="action-buttons-flex">
                      <button class="btn btn-sm btn-outline-primary btn-view-field" data-field="education_certification" data-section="about" data-label="Certification" data-value="<?php echo isset($portfolio['education_certification']) ? htmlspecialchars($portfolio['education_certification']) : ''; ?>" title="View"><i class="fas fa-eye"></i></button>
                      <button class="btn btn-sm btn-outline-secondary btn-edit-field" data-field="education_certification" data-section="about" data-label="Certification" data-value="<?php echo isset($portfolio['education_certification']) ? htmlspecialchars($portfolio['education_certification']) : ''; ?>" data-type="text" title="Edit"><i class="fas fa-edit"></i></button>
                      <button class="btn btn-sm btn-outline-danger btn-clear-field" data-field="education_certification" data-section="about" title="Delete"><i class="fas fa-trash"></i></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- SKILLS SECTION -->
    <div class="section-content section-content-hidden" id="skills-section">
      <div class="search-filter-section">
        <div class="search-wrapper"></div>
        <div class="filter-actions">
          <button class="btn-pill btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addSkillModal"><i class="fas fa-plus me-2"></i>Add Skill</button>
        </div>
      </div>
      <div class="table-section">
        <div class="table-section-wrapper">
          <div class="table-overflow-wrapper">
            <table class="table table-borderless">
              <thead>
                <tr>
                  <th>Skills Titles</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="skillsList">
                <?php if (isset($portfolio['skills']) && is_array($portfolio['skills'])): ?>
                  <?php foreach ($portfolio['skills'] as $skill): ?>
                    <tr data-skill-id="<?php echo isset($skill['id']) ? htmlspecialchars($skill['id']) : ''; ?>">
                      <td class="table-cell-muted"><strong><?php echo htmlspecialchars($skill['name']); ?></strong> - <?php echo htmlspecialchars($skill['percent']); ?>%</td>
                      <td>
                        <div class="action-buttons-flex">
                          <button class="btn btn-sm btn-outline-primary btn-view-field" data-field="skill" data-section="skills" data-label="<?php echo htmlspecialchars($skill['name']); ?>" data-value="<?php echo htmlspecialchars($skill['percent']); ?>%" data-skill-id="<?php echo isset($skill['id']) ? htmlspecialchars($skill['id']) : ''; ?>" data-skill-name="<?php echo htmlspecialchars($skill['name']); ?>" data-skill-percent="<?php echo htmlspecialchars($skill['percent']); ?>" title="View"><i class="fas fa-eye"></i></button>
                          <button class="btn btn-sm btn-outline-secondary btn-edit-field" data-field="skill" data-section="skills" data-label="<?php echo htmlspecialchars($skill['name']); ?>" data-skill-id="<?php echo isset($skill['id']) ? htmlspecialchars($skill['id']) : ''; ?>" data-skill-name="<?php echo htmlspecialchars($skill['name']); ?>" data-skill-percent="<?php echo htmlspecialchars($skill['percent']); ?>" data-type="skill" title="Edit"><i class="fas fa-edit"></i></button>
                          <button class="btn btn-sm btn-outline-danger btn-clear-field" data-field="skill" data-skill-id="<?php echo isset($skill['id']) ? htmlspecialchars($skill['id']) : ''; ?>" data-skill-name="<?php echo htmlspecialchars($skill['name']); ?>" data-section="skills" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- CONTACT SECTION -->
    <div class="section-content section-content-hidden" id="contact-section">
      <div class="search-filter-section">
        <div class="search-wrapper"></div>
        <div class="filter-actions">
          <button class="btn-pill btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addContactModal"><i class="fas fa-plus me-2"></i>Add Contact</button>
        </div>
      </div>
      <div class="table-section">
        <div class="table-section-wrapper">
          <div class="table-overflow-wrapper">
            <table class="table table-borderless">
              <thead>
                <tr>
                  <th>Contact Titles</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="contactsList">
                <?php if (isset($portfolio['contacts']) && is_array($portfolio['contacts'])): ?>
                  <?php 
                  // Icon map for contact types
                  $iconMap = [
                      'Email' => 'fas fa-envelope',
                      'Phone' => 'fas fa-phone',
                      'GitHub' => 'fab fa-github',
                      'LinkedIn' => 'fab fa-linkedin',
                      'Twitter' => 'fab fa-twitter',
                      'Facebook' => 'fab fa-facebook',
                      'Instagram' => 'fab fa-instagram',
                      'YouTube' => 'fab fa-youtube',
                      'TikTok' => 'fab fa-tiktok',
                      'Discord' => 'fab fa-discord',
                      'Telegram' => 'fab fa-telegram',
                      'WhatsApp' => 'fab fa-whatsapp',
                      'Website' => 'fas fa-globe',
                      'Portfolio' => 'fas fa-briefcase',
                      'Address' => 'fas fa-map-marker-alt',
                      'Skype' => 'fab fa-skype',
                      'Slack' => 'fab fa-slack',
                      'Other' => 'fas fa-link'
                  ];
                  ?>
                  <?php foreach ($portfolio['contacts'] as $contact): ?>
                    <?php $iconClass = isset($iconMap[$contact['type']]) ? $iconMap[$contact['type']] : 'fas fa-address-card'; ?>
                    <tr data-contact-id="<?php echo isset($contact['id']) ? htmlspecialchars($contact['id']) : ''; ?>">
                      <td class="table-cell-muted"><i class="<?php echo $iconClass; ?> me-2"></i><strong><?php echo htmlspecialchars($contact['type']); ?>:</strong> <span><?php echo htmlspecialchars($contact['value']); ?></span></td>
                      <td>
                        <div class="action-buttons-flex">
                          <button class="btn btn-sm btn-outline-primary btn-view-field" data-field="contact" data-section="contact" data-label="<?php echo htmlspecialchars($contact['type']); ?>" data-value="<?php echo htmlspecialchars($contact['value']); ?>" data-contact-id="<?php echo isset($contact['id']) ? htmlspecialchars($contact['id']) : ''; ?>" title="View"><i class="fas fa-eye"></i></button>
                          <button class="btn btn-sm btn-outline-secondary btn-edit-field" data-field="contact" data-section="contact" data-label="<?php echo htmlspecialchars($contact['type']); ?>" data-value="<?php echo htmlspecialchars($contact['value']); ?>" data-contact-id="<?php echo isset($contact['id']) ? htmlspecialchars($contact['id']) : ''; ?>" data-type="text" title="Edit"><i class="fas fa-edit"></i></button>
                          <button class="btn btn-sm btn-outline-danger btn-clear-field" data-field="contact" data-section="contact" data-contact-id="<?php echo isset($contact['id']) ? htmlspecialchars($contact['id']) : ''; ?>" title="Delete"><i class="fas fa-trash"></i></button>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr class="no-contacts-row">
                    <td colspan="2" class="text-center text-muted py-4">No contacts added yet. Click "Add Contact" to add one.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- PROJECTS SECTION (default) -->
    <div class="section-content" id="projects-section">
      <!-- Search and table section modeled after the provided template -->
      <div class="search-filter-section">
        <div class="search-wrapper">
          <input type="search" id="projectSearch" class="form-control search-input" placeholder="Search projects by name..." autocomplete="off">
          <div id="projectSearchSuggestions" class="project-search-suggestions d-none" aria-hidden="true"></div>
        </div>
        <div class="filter-actions">
          <button class="btn-pill btn-primary-custom" data-bs-toggle="modal" data-bs-target="#quickCreateModal"><i class="fas fa-plus me-2"></i>Create Project</button>
        </div>
      </div>
    
    <div class="table-section">

      <div class="table-section-wrapper">
        <div class="table-overflow-wrapper">
          <table class="table table-borderless">
            <thead>
              <tr>
                <th>Project Titles</th>
                <th>Image</th>
                <th>URL</th>
                <th>Created</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php if (!empty($events) && is_array($events)): ?>
                <?php foreach ($events as $e): ?>
                  <tr>
                    <td>
                      <div class="project-title-cell">
                        <?php echo htmlspecialchars(isset($e['title']) ? $e['title'] : 'Untitled Project'); ?>
                        <?php if (isset($e['featured']) && $e['featured']): ?><i class="fas fa-star featured-star"></i><?php endif; ?>
                      </div>
                      <div class="project-desc-cell">
                        <?php echo htmlspecialchars(isset($e['description']) ? mb_substr($e['description'], 0, 60) : ''); ?>
                      </div>
                    </td>
                    <td>
                      <?php 
                        $img = isset($e['image']) ? $e['image'] : '';
                        $imgName = $img ? basename($img) : '-';
                      ?>
                      <span class="image-name-text"><?php echo htmlspecialchars($imgName); ?></span>
                    </td>
                    <td class="table-cell-muted">
                      <?php echo htmlspecialchars(isset($e['url']) ? $e['url'] : '-'); ?>
                    </td>
                    <td class="table-cell-muted">
                      <?php echo htmlspecialchars(isset($e['created_at']) ? date('M d, Y', strtotime($e['created_at'])) : ''); ?>
                    </td>
                    <td>
                      <span class="badge bg-light text-muted badge-status">Active</span>
                    </td>
                    <?php
                      $pid = null;
                      if (isset($e['id'])) $pid = $e['id'];
                      elseif (isset($e['project_id'])) $pid = $e['project_id'];
                      $editUrl = $pid ? site_url('projects/edit/'.$pid.'?embedded=1') : '#';
                      $deleteUrl = $pid ? site_url('projects/delete/'.$pid) : '#';
                    ?>
                    <td>
                      <div class="action-buttons-flex">
                        <button class="btn btn-sm btn-outline-primary btn-view" data-id="<?php echo htmlspecialchars($pid); ?>" title="View"><i class="fas fa-eye"></i></button>
                        <button class="btn btn-sm btn-outline-secondary btn-edit" data-id="<?php echo htmlspecialchars($pid); ?>" data-edit-url="<?php echo htmlspecialchars($editUrl); ?>" title="Edit"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-outline-danger btn-delete" data-id="<?php echo htmlspecialchars($pid); ?>" data-delete-url="<?php echo htmlspecialchars($deleteUrl); ?>" title="Delete"><i class="fas fa-trash"></i></button>
                      </div>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr>
                  <td colspan="5">
                    <div class="alert alert-info mb-0">No projects found. Use the <strong>Create Project</strong> button to add one.</div>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    </div>
  </div>

  <?php if (function_exists('get_instance')) {
    $ci = &get_instance();
    $ci->load->view('partials/footer_crud');
  } else {
    if (isset($this) && method_exists($this->load, 'view')) {
      $this->load->view('partials/footer_crud');
    }
  }

  ?>

  <!-- Edit Home Modal -->
  <div class="modal fade" id="editHomeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-home me-2"></i>Edit Home Section</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editHomeForm" enctype="multipart/form-data">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <div class="modal-body">
            <div class="form-row cols-2">
              <div class="form-full-width">
                <label class="form-label">Profile Image</label>
                <div class="profile-preview-container">
                  <img id="profilePreview" src="<?php echo base_url('assets/img/profiles/profile.png'); ?>?t=<?php echo time(); ?>" alt="Profile Preview">
                </div>
                <input type="file" id="profileImageInput" class="form-control" accept="image/*" >
                <small class="form-text text-muted">Upload a PNG or JPG image (max 5MB)</small>
              </div>
              <div class="form-full-width">
                <label class="form-label">Profile Name</label>
                <input type="text" class="form-control" value="<?php echo isset($portfolio['hero_title']) ? htmlspecialchars($portfolio['hero_title']) : 'Miguel Andrei del Rosario'; ?>" placeholder="Your full name">
              </div>
              <div class="form-full-width">
                <label class="form-label">Subtitle</label>
                <input type="text" class="form-control" value="<?php echo isset($portfolio['hero_subtitle']) ? htmlspecialchars($portfolio['hero_subtitle']) : 'A Web Developer Trainee'; ?>" placeholder="Your professional title">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-pill btn-primary-custom">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit About Me Modal -->
  <div class="modal fade" id="editAboutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-user me-2"></i>Edit About Me Section</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editAboutForm">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <div class="modal-body">
            <div class="form-row cols-1">
              <div class="form-full-width">
                <label class="form-label">About Content</label>
                <textarea class="form-control" rows="6" placeholder="Write about yourself..."><?php echo isset($portfolio['about_content']) ? htmlspecialchars($portfolio['about_content']) : 'I\'m a motivated Information Technology student passionate about creating innovative web solutions.'; ?></textarea>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-pill btn-primary-custom">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Education Modal -->
  <div class="modal fade" id="editEducationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-graduation-cap me-2"></i>Edit Education Section</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editEducationForm">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <div class="modal-body">
            <div class="form-row cols-1">
              <div class="form-full-width">
                <label class="form-label">Elementary School</label>
                <input type="text" name="education_elementary" class="form-control" placeholder="e.g., Talon Elementary School (2008-2014)" value="<?php echo isset($portfolio['education_elementary']) ? htmlspecialchars($portfolio['education_elementary']) : ''; ?>">
              </div>
              <div class="form-full-width">
                <label class="form-label">High School</label>
                <input type="text" name="education_high_school" class="form-control" placeholder="e.g., City of Bacoor National High School (2015-2019)" value="<?php echo isset($portfolio['education_high_school']) ? htmlspecialchars($portfolio['education_high_school']) : ''; ?>">
              </div>
              <div class="form-full-width">
                <label class="form-label">Senior High School</label>
                <input type="text" name="education_senior_high" class="form-control" placeholder="e.g., Las Piñas City National Senior High School (2020-2021)" value="<?php echo isset($portfolio['education_senior_high']) ? htmlspecialchars($portfolio['education_senior_high']) : ''; ?>">
              </div>
              <div class="form-full-width">
                <label class="form-label">College / University</label>
                <input type="text" name="education_college" class="form-control" placeholder="e.g., St. Dominic College of Asia - B.S. Information Technology" value="<?php echo isset($portfolio['education_college']) ? htmlspecialchars($portfolio['education_college']) : ''; ?>">
              </div>
              <div class="form-full-width">
                <label class="form-label">Certification</label>
                <input type="text" name="education_certification" class="form-control" placeholder="e.g., Information Technology Specialist - HTML and CSS" value="<?php echo isset($portfolio['education_certification']) ? htmlspecialchars($portfolio['education_certification']) : ''; ?>">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-pill btn-primary-custom">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Skills Modal -->
  <div class="modal fade" id="editSkillsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-star me-2"></i>Edit Skills Section</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editSkillsForm">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <div class="modal-body">
            <?php if (isset($portfolio['skills']) && is_array($portfolio['skills'])): ?>
              <?php foreach ($portfolio['skills'] as $skill): ?>
            <div class="skill-edit-row">
              <div><label class="form-label">Skill Name</label><input type="text" class="form-control skill-name" value="<?php echo htmlspecialchars($skill['name']); ?>"></div>
              <div><label class="form-label">Proficiency %</label><input type="number" class="form-control skill-percent" value="<?php echo htmlspecialchars($skill['percent']); ?>" min="0" max="100"></div>
            </div>
              <?php endforeach; ?>
            <?php endif; ?>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-pill btn-primary-custom">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Contact Modal -->
  <div class="modal fade" id="editContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-envelope me-2"></i>Edit Get in Touch Section</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editContactForm">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <div class="modal-body">
            <div class="form-row cols-2">
              <div>
                <label class="form-label">Email</label>
                <input type="email" class="form-control" value="<?php echo isset($portfolio['email']) ? htmlspecialchars($portfolio['email']) : 'miguelandrei@sdca.edu.ph'; ?>">
              </div>
              <div>
                <label class="form-label">Phone</label>
                <input type="tel" class="form-control" value="<?php echo isset($portfolio['phone']) ? htmlspecialchars($portfolio['phone']) : '639096059630'; ?>">
              </div>
              <div>
                <label class="form-label">GitHub URL</label>
                <input type="url" class="form-control" value="<?php echo isset($portfolio['github_url']) ? htmlspecialchars($portfolio['github_url']) : 'https://github.com/migs159'; ?>">
              </div>
              <div>
                <label class="form-label">LinkedIn URL</label>
                <input type="url" class="form-control" value="<?php echo isset($portfolio['linkedin_url']) ? htmlspecialchars($portfolio['linkedin_url']) : 'https://www.linkedin.com/in/miguel-andrei-del-rosario-a291693b1/'; ?>">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-pill btn-primary-custom">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Quick Create Modal -->
  <div class="modal fade" id="quickCreateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Create Project</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="post" action="<?php echo site_url('projects/create'); ?>" enctype="multipart/form-data">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="return_to" value="crud">
          <div class="modal-body">
              <div class="form-row cols-2">
                <div>
                  <label class="form-label">Title</label>
                  <input name="title" class="form-control" required placeholder="Project title">
                  <div class="form-text">Give a concise, descriptive title.</div>
                </div>
                <!-- Tags removed per request -->
                <div class="modal-form-column">
                  <label class="form-label">Framework/Language</label>
                  <select name="type[]" class="form-control" multiple size="6">
                    <option value="" disabled>--Select framework / language-- </option>
                    <option value="php">PHP</option>
                    <option value="javascript">JavaScript</option>
                    <option value="html_css">HTML/CSS</option>
                    <option value="nodejs">Node.js</option>
                    <option value="react">React</option>
                    <option value="vue">Vue.js</option>
                    <option value="angular">Angular</option>
                    <option value="sql">SQL</option>
                    <option value="mysql">MySQL</option>
                    <option value="uiux">UI/UX</option>
                    <option value="cli">CLI / Tools</option>
                    <option value="devops">DevOps</option>
                  </select>
                  <div class="form-text">Choose one or more frameworks or languages (hold Ctrl / Cmd to multi-select).</div>
                </div>

                <div class="form-full-width">
                  <label class="form-label">Description</label>
                  <textarea name="description" class="form-control" rows="4" placeholder="Short description"></textarea>
                </div>

                <div>
                  <label class="form-label">Project Image</label>
                  <input type="file" name="image" class="form-control image-input" accept="image/png,image/jpeg,.png,.jpg,.jpeg">
                  <div class="form-text">Upload a PNG or JPG image (max 5MB)</div>
                  <div class="image-preview-container">
                    <img src="" alt="Preview" class="image-preview-img">
                  </div>
                </div>
                <div>
                  <label class="form-label">Link URL</label>
                  <input name="url" class="form-control" placeholder="https://...">
                </div>

                <div class="featured-checkbox-wrapper-full">
                  <input type="hidden" name="featured" value="0" id="featuredHidden">
                  <input type="checkbox" id="featuredCheckbox" class="form-check-input featured-checkbox-input">
                  <label for="featuredCheckbox" class="featured-checkbox-label-text">Mark as featured</label>
                </div>
              </div>
            </div>
          <div class="modal-footer">
            <button type="button" class="btn-pill btn-ghost" id="quickCreateCancelBtn" data-bs-dismiss="modal" aria-label="Cancel">Cancel</button>
            <button type="submit" class="btn-pill btn-primary-custom">Create</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Account Modal (redesigned) -->
  <?php $user_email = isset($user['email']) ? $user['email'] : (isset($_SESSION['email']) ? $_SESSION['email'] : 'Not set');
        $profile_initial = isset($_SESSION['username']) && $_SESSION['username'] ? strtoupper(substr(trim($_SESSION['username']),0,1)) : 'M';
        $profile_name = isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Miguel Del Rosario';
  ?>
  <div class="modal fade" id="viewAccountModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
      <div class="modal-content">
        <div class="modal-body p-0">
          <div class="account-card">
            <div class="account-cover" role="img" aria-label="Cover image">
              <button class="btn btn-sm btn-outline-light edit-cover" title="Edit Cover"><i class="fas fa-camera"></i> Edit Cover</button>
            </div>
            <div class="account-card-inner container">
              <div class="d-flex align-items-start justify-content-between pt-4">
                <div class="d-flex align-items-center">
                  <div class="avatar me-4">
                    <?php if (isset($profile_img) && $profile_img): ?>
                      <img src="<?php echo htmlspecialchars($profile_img); ?>" alt="Profile" />
                    <?php else: ?>
                      <span class="avatar-initial"><?php echo htmlspecialchars($profile_initial); ?></span>
                    <?php endif; ?>
                  </div>
                  <div>
                    <h3 class="mb-1 account-name"><?php echo $profile_name; ?></h3>
                    <div class="text-muted small">BS in Information Technology</div>
                    <div class="d-flex gap-3 text-muted small mt-2">
                      <div><i class="fas fa-graduation-cap me-1"></i>Graduated 2026</div>
                      <div><i class="fas fa-id-card me-1"></i>ID: 202201070</div>
                    </div>
                  </div>
                </div>
                <div class="ms-3">
                  <button class="btn btn-primary btn-edit-profile"><i class="fas fa-edit me-1"></i>Edit Profile</button>
                </div>
              </div>

              <hr class="my-4">

              <div class="row mb-4">
                <div class="col-md-4">
                  <div class="text-muted small">EMAIL</div>
                  <div class="fw-bold"><?php echo !empty($user_email) ? htmlspecialchars($user_email) : 'Not set'; ?></div>
                </div>
                <div class="col-md-4">
                  <div class="text-muted small">ALTERNATE EMAIL</div>
                  <div class="fw-bold">Not Set</div>
                </div>
                <div class="col-md-4">
                  <div class="text-muted small">PHONE</div>
                  <div class="fw-bold"><?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : 'Not set'; ?></div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Reusable iframe modal for View/Edit/Manage -->
  <div class="modal fade" id="iframeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="iframeModalTitle"><i class="fas fa-edit me-2"></i><span id="iframeModalLabel">Edit Project</span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="iframe-loading-hidden" id="iframeLoading"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>
          <iframe id="iframeModalFrame" src="" class="iframe-wrap"></iframe>
        </div>
      </div>
    </div>
  </div>

  <!-- View Field Modal (Dynamic) -->
  <div class="modal fade" id="viewFieldModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-eye me-2"></i><span id="viewFieldTitle">View Field</span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label fw-bold" id="viewFieldLabel">Field</label>
            <div id="viewFieldValue" class="form-control-plaintext"></div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Field Modal (Dynamic) -->
  <div class="modal fade" id="editFieldModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-edit me-2"></i><span id="editFieldTitle">Edit Field</span></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editFieldForm">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" id="editFieldName" name="field" value="">
          <input type="hidden" id="editFieldSection" name="section" value="">
          <input type="hidden" id="editFieldSkillName" name="skill_name" value="">
          <div class="modal-body">
            <div class="mb-3" id="editFieldInputWrapper">
              <label class="form-label fw-bold" id="editFieldLabel">Field</label>
              <!-- Input will be dynamically inserted here -->
            </div>
            <!-- Skill-specific fields (hidden by default) -->
            <div class="mb-3" id="editSkillPercentWrapper">
              <label class="form-label fw-bold">Proficiency %</label>
              <input type="number" id="editSkillPercent" name="skill_percent" class="form-control" min="0" max="100" value="">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-pill btn-primary-custom">Save Changes</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Add Skill Modal -->
  <div class="modal fade" id="addSkillModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Skill</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addSkillForm">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="section" value="skills">
          <input type="hidden" name="field" value="skill">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-bold">Select Skill</label>
              <select name="skill_name" id="addSkillName" class="form-control" required>
                <option value="" disabled selected>-- Select a skill --</option>
                <option value="HTML5 / CSS3">HTML5 / CSS3</option>
                <option value="JavaScript">JavaScript</option>
                <option value="PHP">PHP</option>
                <option value="Python">Python</option>
                <option value="Java">Java</option>
                <option value="C#">C#</option>
                <option value="C++">C++</option>
                <option value="Ruby">Ruby</option>
                <option value="Swift">Swift</option>
                <option value="Kotlin">Kotlin</option>
                <option value="TypeScript">TypeScript</option>
                <option value="React">React</option>
                <option value="Vue.js">Vue.js</option>
                <option value="Angular">Angular</option>
                <option value="Node.js">Node.js</option>
                <option value="Express.js">Express.js</option>
                <option value="Laravel">Laravel</option>
                <option value="CodeIgniter">CodeIgniter</option>
                <option value="Django">Django</option>
                <option value="Flask">Flask</option>
                <option value="Bootstrap">Bootstrap</option>
                <option value="Tailwind CSS">Tailwind CSS</option>
                <option value="jQuery">jQuery</option>
                <option value="MySQL">MySQL</option>
                <option value="PostgreSQL">PostgreSQL</option>
                <option value="MongoDB">MongoDB</option>
                <option value="SQL Server">SQL Server</option>
                <option value="GitHub">GitHub</option>
                <option value="Docker">Docker</option>
                <option value="AWS">AWS</option>
                <option value="Linux">Linux</option>
                <option value="UI/UX Design">UI/UX Design</option>
                <option value="Figma">Figma</option>
                <option value="Adobe Photoshop">Adobe Photoshop</option>
                <option value="Adobe Illustrator">Adobe Illustrator</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Proficiency %</label>
              <input type="number" name="skill_percent" id="addSkillPercent" class="form-control" min="0" max="100" placeholder="e.g., 85" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-pill btn-primary-custom">Add Skill</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Add Contact Modal -->
  <div class="modal fade" id="addContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Add Contact</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addContactForm">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <input type="hidden" name="section" value="contact">
          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label fw-bold">Contact Type</label>
              <select name="field" id="addContactType" class="form-select" required>
                <option value="" disabled selected>Select contact type...</option>
                <option value="Email" data-icon="fas fa-envelope">📧 Email</option>
                <option value="Phone" data-icon="fas fa-phone">📱 Phone</option>
                <option value="GitHub" data-icon="fab fa-github">🐙 GitHub</option>
                <option value="LinkedIn" data-icon="fab fa-linkedin">💼 LinkedIn</option>
                <option value="Twitter" data-icon="fab fa-twitter">🐦 Twitter</option>
                <option value="Facebook" data-icon="fab fa-facebook">📘 Facebook</option>
                <option value="Instagram" data-icon="fab fa-instagram">📷 Instagram</option>
                <option value="YouTube" data-icon="fab fa-youtube">🎬 YouTube</option>
                <option value="TikTok" data-icon="fab fa-tiktok">🎵 TikTok</option>
                <option value="Discord" data-icon="fab fa-discord">💬 Discord</option>
                <option value="Telegram" data-icon="fab fa-telegram">✈️ Telegram</option>
                <option value="WhatsApp" data-icon="fab fa-whatsapp">💬 WhatsApp</option>
                <option value="Website" data-icon="fas fa-globe">🌐 Website</option>
                <option value="Portfolio" data-icon="fas fa-briefcase">💼 Portfolio</option>
                <option value="Address" data-icon="fas fa-map-marker-alt">📍 Address</option>
                <option value="Skype" data-icon="fab fa-skype">💠 Skype</option>
                <option value="Slack" data-icon="fab fa-slack">🔷 Slack</option>
                <option value="Other" data-icon="fas fa-link">🔗 Other</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold">Value</label>
              <input type="text" name="value" id="addContactValue" class="form-control" placeholder="Enter value" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn-pill btn-ghost" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-pill btn-primary-custom">Add Contact</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Project Details Modal (admin) -->
  <?php if (function_exists('get_instance')) { $ci = &get_instance(); $ci->load->view('partials/modal_view_project_admin'); } else { if (isset($this) && method_exists($this->load,'view')) { $this->load->view('partials/modal_view_project_admin'); } } ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="<?php echo htmlspecialchars(function_exists('base_url') ? base_url('assets/js/crud_dashboard.js') : '/assets/js/crud_dashboard.js'); ?>" data-edit-section-url="<?php echo site_url('crud/edit_section'); ?>" data-flash-success="<?php echo htmlspecialchars($this->session->flashdata('success') ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-flash-error="<?php echo htmlspecialchars($this->session->flashdata('error') ?? '', ENT_QUOTES, 'UTF-8'); ?>"></script>
  </body>
</html>
