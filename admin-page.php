<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$opm_user      = maybe_unserialize( get_option( 'opm_user' ) );
$opm_campaigns = maybe_unserialize( get_option( 'opm_campaigns' ) );
$api_key       = $opm_user && $opm_user['api_key'] ? $opm_user['api_key'] : null;
$opm_mapping   = maybe_unserialize( get_option( 'opm_mapping', [] ) );

?>

<div id="app">
    <div class="">
        <aside class="">
            <div class="container" style='max-width: 1140px'>
                <img class='opm-logo py-4'
                     src="<?php echo plugin_dir_url( __FILE__ ) . '/images/opm-logo.png'; ?>"
                     alt="">
            </div>
        </aside>
        <main class="container" style='max-width: 1140px'>
            <div class="row gx-5">
                <div class="col-sm-2">
                    <ul class="nav nav-pills nav-fill flex-column">
                        <li class="nav-item text-primary" role="presentation">
                            <button class="nav-link active" id="license-tab" data-bs-toggle="tab"
                                    data-bs-target="#license-tab-pane"
                                    type="button" role="tab" aria-controls="license-tab-pane"
                                    aria-selected="true"><?php _e( 'API Key', 'optinmagic' ) ?>
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="campaigns-tab" data-bs-toggle="tab" @click="getCampaigns"
                                    data-bs-target="#campaigns-tab-pane"
                                    type="button" role="tab" aria-controls="campaigns-tab-pane"
                                    aria-selected="false"><?php _e( 'Campaigns', 'optinmagic' ) ?>
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="col-sm-10">
                    <div class="tab-content">
                        <div class="tab-pane fade show active" id="license-tab-pane" role="tabpanel"
                             aria-labelledby="license-tab"
                             tabindex="0">
                            <form id="api_key_form">
                                <div class="mb-3">
                                    <label for="api_key"
                                           class="form-label sec-title"><?php _e( 'API Key', 'optinmagic' ) ?></label>


                                    <div class="input-group mb-3"
                                         style="border: 1px solid #ced4da; border-radius: .3em">
                                        <input style="border-radius:.3em" value="<?php esc_attr_e( $api_key ); ?>"
                                               name="x-api-key"
                                               type="text" class="form-control border-0" id="api_key"
                                               aria-describedby="apiKeyHelp">
                                        <div class="input-group-append">
                                            <span class="input-group-text bg-transparent border-0"
                                                  style="height:40px;border-top-left-radius: 0;border-bottom-left-radius: 0">
                                                <svg fill="#0d6efd" style="display: none;" class="success-icon"
                                                     height="20px"
                                                     version="1.1"
                                                     xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000"
                                                     xml:space="preserve">
                                                    <g><path d="M846.5,153.5C753.9,61,630.9,10,500,10c-130.9,0-254,51-346.5,143.5C61,246.1,10,369.2,10,500.1C10,631,61,754,153.5,846.5C246.1,939,369.1,990,500,990c130.9,0,254-51,346.5-143.5C939,754,990,630.9,990,500.1C990,369.1,939,246.1,846.5,153.5z M925.4,500c0,234.6-190.8,425.4-425.4,425.4C265.4,925.4,74.6,734.6,74.6,500C74.6,265.4,265.4,74.6,500,74.6C734.6,74.6,925.4,265.4,925.4,500z"/><path
                                                                d="M754.2,289.7c-8.6,0-16.7,3.4-22.8,9.4L398.2,632.3L268.5,502.6c-6.1-6.1-14.2-9.4-22.8-9.4l0,0c-8.6,0-16.7,3.4-22.8,9.4c-6.1,6.1-9.4,14.2-9.4,22.8s3.4,16.7,9.4,22.8l152.6,152.6c6.1,6,14.2,9.4,22.7,9.4h0.1l0,0c8.6,0,16.7-3.4,22.8-9.4l356-356c12.6-12.6,12.6-33.1,0-45.7C770.9,293.1,762.8,289.7,754.2,289.7z"/></g>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>

                                    <div id="apiKeyHelp"
                                         class="form-text">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13"
                                             fill="currentColor"
                                             class="mb-1 me-1" viewBox="0 0 16 16">
                                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                                        </svg>
										<?php _e( 'You\'ll find your OptinMagic API key', 'optinmagic' ) ?> <a
                                                href="https://optinmagic.io/account"
                                                target="_blank"><?php _e( 'here', 'optinmagic' ) ?></a>.
                                    </div>
                                </div>
                                <button type="button" @click="validateAPIKey" id="submit_api_key"
                                        class="btn btn-warning px-5 py-2 "><?php _e( 'Submit', 'optinmagic' ) ?></button>
                            </form>
                        </div>
                        <div class="tab-pane fade" id="campaigns-tab-pane" role="tabpanel"
                             aria-labelledby="campaigns-tab"
                             tabindex="0">
                            <div class='sec-title d-flex justify-content-between'>
                                <span><?php _e( 'All Campaigns', 'optinmagic' ); ?> </span>
                                <a target="_blank" class="btn btn-primary btn-sm" href="https://optinmagic.io/new_campaign">
                                    <svg style="margin-bottom: 2px" xmlns="http://www.w3.org/2000/svg" width="20"
                                         height="20" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg><?php _e( 'Create', 'optinmagic' ); ?></a>
                            </div>
                            <div id="campaigns">
                                <table class="table mb-0">
                                    <thead>
                                    <tr>
                                        <th scope="col"><?php _e( 'Name', 'optinmagic' ); ?></th>
                                        <th scope="col"><?php _e( 'Viewed' ) ?></th>
                                        <th scope="col"><?php _e( 'Status', 'optinmagic' ) ?></th>
                                        <th scope="col"><?php _e( 'Actions', 'optinmagic' ) ?></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="campaign in campaigns">
                                        <td>{{campaign.name}}</td>
                                        <td>{{campaign.viewed}}</td>
                                        <td>{{campaign.status}}</td>
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm me-1"
                                                    data-bs-toggle="modal"
                                                    style="--bs-btn-padding-y: .2rem; --bs-btn-padding-x: .25rem; --bs-btn-font-size: .75rem;"
                                                    @click="applySelect2(campaign.id)"
                                                    v-bind:data-bs-target="'#modal-campaign-'+campaign.id">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                     fill="currentColor" class="bi bi-link-45deg" viewBox="0 0 16 16">
                                                    <path d="M4.715 6.542 3.343 7.914a3 3 0 1 0 4.243 4.243l1.828-1.829A3 3 0 0 0 8.586 5.5L8 6.086a1.002 1.002 0 0 0-.154.199 2 2 0 0 1 .861 3.337L6.88 11.45a2 2 0 1 1-2.83-2.83l.793-.792a4.018 4.018 0 0 1-.128-1.287z"/>
                                                    <path d="M6.586 4.672A3 3 0 0 0 7.414 9.5l.775-.776a2 2 0 0 1-.896-3.346L9.12 3.55a2 2 0 1 1 2.83 2.83l-.793.792c.112.42.155.855.128 1.287l1.372-1.372a3 3 0 1 0-4.243-4.243L6.586 4.672z"/>
                                                </svg>
                                                </i> <?php _e( 'Assign' ); ?>
                                            </button>
                                            <a :href="'https://optinmagic.io/builder/'+campaign.id" target="_blank"
                                               style="--bs-btn-padding-y: .235rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
                                               type="button" class="btn btn-success btn-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                     fill="currentColor" class="bi bi-pencil" viewBox="0 0 16 16">
                                                    <path d="M12.146.146a.5.5 0 0 1 .708 0l3 3a.5.5 0 0 1 0 .708l-10 10a.5.5 0 0 1-.168.11l-5 2a.5.5 0 0 1-.65-.65l2-5a.5.5 0 0 1 .11-.168l10-10zM11.207 2.5 13.5 4.793 14.793 3.5 12.5 1.207 11.207 2.5zm1.586 3L10.5 3.207 4 9.707V10h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.293l6.5-6.5zm-9.761 5.175-.106.106-1.528 3.821 3.821-1.528.106-.106A.5.5 0 0 1 5 12.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.468-.325z"/>
                                                </svg>
												<?php _e( 'Edit' ); ?>
                                            </a>

                                            <div class="modal fade" v-bind:id="'modal-campaign-'+campaign.id"
                                                 tabindex="-1"
                                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h6 class="modal-title"
                                                                id="exampleModalLabel"><?php _e( 'Select posts show this Campaign in', 'optinmagic' ) ?></h6>
                                                            <button type="button" class="btn-close btn-sm small"
                                                                    data-bs-dismiss="modal"
                                                                    aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <select :id="'select-posts-'+campaign.id"
                                                                    class="select-posts"
                                                                    multiple="multiple"
                                                                    name="posts[]">
                                                                <option v-for="post in posts" :value="post.id">
                                                                    {{post.title}}
                                                                </option>

                                                            </select>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger btn-sm px-3"
                                                                    style="--bs-btn-padding-y: .2rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
                                                                    data-bs-dismiss="modal"><?php _e( 'Close', 'optinmagic' ) ?>
                                                            </button>
                                                            <button @click="saveAssignment(campaign.id)" type="button"
                                                                    style="--bs-btn-padding-y: .2rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;"
                                                                    class="btn btn-primary btn-sm px-3"><?php _e( 'Save', 'optinmagic' ) ?>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
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
    </div>
</div>

<div id="mapping_toast" class="toast align-items-center border-0 position-fixed" role="alert"
     aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-info">
        <strong class="me-auto text-white"><?php _e( 'Success', 'optinmagic' ); ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
		<?php _e( 'Campaign successfully assigned to selected posts.', 'optinmagic' ); ?>
    </div>
</div>
<div id="api_toast" class="toast align-items-center border-0 position-fixed " role="alert"
     aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-info">
        <strong class="me-auto text-white"><?php _e( 'Success', 'optinmagic' ); ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
		<?php _e( 'API key validated successfully.', 'optinmagic' ); ?>
    </div>
</div>
<div id="api_toast_fail" class="toast align-items-center border-0 position-fixed" role="alert"
     aria-live="assertive" aria-atomic="true">
    <div class="toast-header bg-danger">
        <strong class="me-auto text-white"><?php _e( 'Failed', 'optinmagic' ); ?></strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
    <div class="toast-body">
		<?php _e( 'API key validation failed.', 'optinmagic' ); ?>
    </div>
</div>
</div>

<script>

    const {createApp} = Vue;

    let $ = jQuery;
    let api_base = 'https://api.optinmagic.io';
    let options = [];

    // const modals = document.querySelectorAll('.modal');
    // modals.forEach(modal => {
    //     $(modal).on('hidden.bs.modal', (e) => {
    //     });
    // })

    const vueApp = createApp({
        data() {
            return {
                count: 0,
                api_key: '<?php esc_attr_e( $api_key );?>',
                campaigns: [],
                posts: [],
                selectedPosts: [],
                mapping: [],
            }
        },
        methods: {
            getCampaigns() {
                _this = this;
                $.ajax({
                    type: 'get',
                    beforeSend: function (request) {
                        request.setRequestHeader('X-API-KEY', _this.api_key);
                    },
                    url: api_base + '/wp/campaigns',
                    processData: false,
                    success: function (campaigns) {
                        _this.campaigns = campaigns;
                    },
                    error: function (error) {
                        return false;
                    }
                });

            },
            applySelect2(campaign_id) {
                _this = this;
                $('#select-posts-' + campaign_id)
                    .val(_this.mapping[campaign_id] ? _this.mapping[campaign_id] : _this.selectedPosts)
                    .select2({width: '100%'})
                    .on("change", function (e) {
                        _this.selectedPosts = $(e.target).val();
                    });

            },
            getPosts() {
                _this = this;
                $.post(
                    ajaxurl,
                    {
                        'action': 'opm_search_posts',
                        'post_title': '',
                    },
                    function (response) {
                        // Handle the response
                        try {
                            _this.posts = JSON.parse(response)
                        } catch (e) {
                            console.log(e)
                        }
                    }
                );
            },
            saveAssignment(id) {
                _this = this;
                $('#modal-campaign-' + id).modal('hide');
                // update the mapping too
                _this.mapping[id] = _this.selectedPosts;
                $.post(
                    ajaxurl,
                    {
                        'action': 'opm_save_campaign_assignment',
                        'campaign_id': id,
                        'posts_ids': _this.selectedPosts,
                    },
                    function (response) {
                        // Handle the response
                        showToast('mapping_toast');
                    }
                );
            },
            getCampaignAssignment(id) {
                _this = this;
                $.post(
                    ajaxurl,
                    {
                        'action': 'opm_get_campaign_assignment'
                    },
                    function (response) {
                        _this.mapping = JSON.parse(response)
                    }
                );
            },
            validateAPIKey() {
                _this = this;
                // disable submit button
                $('#submit_api_key').prop("disabled", true);
                let value = $('#api_key').val();
                $.ajax({
                    type: 'get',
                    beforeSend: function (request) {
                        request.setRequestHeader('X-API-KEY', value);
                    },
                    url: api_base + '/wp/check_api_key',
                    processData: false,
                    success: function (msg) {
                        _this.getCampaigns();
                        // enable submit button
                        $('#submit_api_key').prop("disabled", false);

                        $('#license-tab-pane .alert').each((indx, alrt) => $(alrt).remove());
                        _this.api_key = value;
                        let data = {
                            'action': 'opm_save_user',
                            'api_key': value,
                            ...msg
                        };
                        $.post(ajaxurl, data, function (response) {
                            $('.success-icon').css('display', 'block');
                            $('.input-group').css('border-color', '#0d6efd');
                        });
                        showToast('api_toast');
                        return false;
                    },
                    error: function (error) {
                        // enable submit button
                        $('#submit_api_key').prop("disabled", false);
                        showToast('api_toast_fail');
                        return false;
                    }
                });
            }
        },
        mounted() {
            // methods can be called in lifecycle hooks, or other methods!
            this.getPosts();
            this.getCampaignAssignment();
        }
    });

    const vm = vueApp.mount('#app');

    function showToast(id) {
        let myToastEl = document.getElementById(id)
        let myToast = new bootstrap.Toast(myToastEl) // Returns a Bootstrap toast instance
        myToast.show();
    }
</script>
