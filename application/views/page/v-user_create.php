<div class="panel panel-primary">
    <div class="panel-heading">
        <b>Create User</b>
        <div class="pull-right">
            <a class="btn btn-default btn-xs" href="#" onclick="BukaPage('user')">kembali</a>
        </div>
    </div>
    <div class="panel-body">
        <form id="formku" role="form" method="post">
            <div class="col-lg-3">
                <p>
                    <label for="username">Username:</label><br />
                    <input type="text" name="username" value="" id="username"  />
                </p>
                <p>
                    <label for="first_name">First Name:</label> <br />
                    <input type="text" name="first_name" value="" id="first_name"  />
                </p>
                <p>
                    <label for="last_name">Last Name:</label> <br />
                    <input type="text" name="last_name" value="" id="last_name"  />
                </p>
                <p>
                    <label for="company">Company Name:</label> <br />
                    <input type="text" name="company" value="" id="company"  />
                </p>
            </div>

            <div class="col-lg-3">
                <p>
                    <label for="email">Email:</label> <br />
                    <input type="text" name="email" value="" id="email"  />
                </p>
                <p>
                    <label for="phone">Phone:</label> <br />
                    <input type="text" name="phone" value="" id="phone"  />
                </p>
                <p>
                    <label for="password">Password:</label> <br />
                    <input type="password" name="password" value="" id="password"  />
                </p>
                <p>
                    <label for="password_confirm">Confirm Password:</label> <br />
                    <input type="password" name="password_confirm" value="" id="password_confirm"  />
                </p>
                <p>
                    <input class="btn btn-primary btn-xs" type="submit" name="submit" value="Create User"  />
                </p>
            </div>
        </form>   
    </div>
    <div class="panel-footer">
        Page rendered in <strong>0.0510</strong> seconds.
        &nbsp;
    </div>
</div>

<script>
    $('#formku').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            dataType: 'html',
            type: 'post',
            url: '<?= base_url('user/create_user'); ?>', data: $('#formku').serialize(),
            //beforeSubmit: validator,
            success: function(result) {
                if (result === 'success') {
                    $.notify(result, {
                        className: 'success',
                    });
                    BukaPage('user');
                } else {
                    $.notify(result, {
                        className: 'error',
                    });
                }
            },
            error: function(responseData) {
                window.location.href = '';
            }
        });
    })
</script>