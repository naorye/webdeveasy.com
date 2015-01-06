(function(Views, Models, $, Backbone) {

	Views.UserProfile = Backbone.View.extend({
		el: 'body',

		events: {
			'click a.show-edit-form': 'openEditProfileForm',
			'click a.delete-current-user': 'selfDelete'
		},

		initialize: function() {
			//console.log('profile init');
			this.user = new Models.User(currentUser);
			this.blockUi = new AE.Views.BlockUi();
		},
		selfDelete: function(event) {
			event.preventDefault();
			var target = $(event.currentTarget),
				view = this;

			bootbox.confirm("Are you sure want tot delete your account? It can't be undone.", function(result) {
				if (result == true) {
					view.user.destroy({
						beforeSend: function() {
							view.blockUi.block(target);
						},
						success: function(result, status, jqXHR) {
							view.blockUi.unblock();
							if (status.success) {
								//window.location.href = status.redirect;
							} else {
								//bootbox.alert(status.msg);
								AE.pubsub.trigger('ae:notification', {
									msg: status.msg,
									notice_type: 'error',
								});								
							}
						}
					});
				}
			});

		},
		openEditProfileForm: function(event) {
			event.preventDefault();
			var modal = new Views.EditProfileModal({
				el: $("#edit_profile")
			});
			modal.openModal();
		}
	});

})(QAEngine.Views, QAEngine.Models, jQuery, Backbone);