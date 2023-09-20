<?php if (!isset($conn)) {
    include 'db_connect.php';
}

$groupUser = $conn->query("SELECT concat(users.child_group,',',users.group_id) as groups FROM users where users.id =  '" . $_SESSION['login_id'] . "'");
$group_User = $groupUser->fetch_assoc()['groups'];

?>
<div class="card card-outline card-primary" style=" direction: rtl;text-align: right;">
    <div class="card-body">
        <form action="" id="manage-project">

            <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">نام</label>
                        <input type="text" class="form-control form-control-sm" name="name"
                               value="<?php echo isset($name) ? $name : '' ?>">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">وضعیت</label>
                        <select name="status" id="status" class="custom-select custom-select-sm">
                            <option value="0" <?php echo isset($status) && $status == 0 ? 'selected' : '' ?>>

                            </option>
                            <option value="3" <?php echo isset($status) && $status == 3 ? 'selected' : '' ?>>در حال
                                انتظار
                            </option>
                            <option value="2" <?php echo isset($status) && $status == 2 ? 'selected' : '' ?>>در حال پیشرفت
                            </option>
                            <option value="1" <?php echo isset($status) && $status == 1 ? 'selected' : '' ?>>آغاز
                                شده
                            </option>
                            <option value="5" <?php echo isset($status) && $status == 5 ? 'selected' : '' ?>>انجام
                                شده
                            </option>
                            <option value="4" <?php echo isset($status) && $status == 4 ? 'selected' : '' ?>>سررسید
                            </option>
                            <option value="6" <?php echo isset($status) && $status == 6 ? 'selected' : '' ?>>در
                                انتظار
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">تاریخ شروع</label>
                        <input type="text" class="form-control form-control-sm " id="start-date-id"
                               autocomplete="off" name="start_date" value="<?php echo isset($start_date) ?  date(" Y/m/d",strtotime($start_date)) : '' ?>">
                    </div>

                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="" class="control-label">تاریخ سررسید</label>
                        <input type="text" id="end-date-id" class="form-control form-control-sm" autocomplete="off"
                               name="end_date" value="<?php echo isset($end_date) ?  date(" Y/m/d",strtotime($end_date)) : '' ?>">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group" style="text-align: right">
                        <label for="" class="control-label">گروه کاربری</label>
                        <select id="group" class="form-control form-control-sm select2" name="group_id">
                            <option></option>
                            <?php

                            $groups = $conn->query("SELECT DISTINCT user_group.group_id,user_group.group_name as name FROM user_group LEFT JOIN users ON users.group_id = user_group.group_id where user_group.group_id IN($group_User)  order by user_group.group_name asc ;");
                            while ($row = $groups->fetch_assoc()):
                                ?>
                                <option value="<?php echo $row['group_id'] ?>" <?php echo isset($group_id) && $group_id == $row['group_id'] ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
                            <?php endwhile; ?>

                        </select>
                    </div>
                </div>
                <?php if ($_SESSION['login_type'] == 0): ?>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="" class="control-label">مدیر پروژه</label>
                            <select id="manage" class="form-control form-control-sm select2" name="manager_id">
                                <option></option>
                                <?php
                                $managers = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where (users.id =  '" . $_SESSION['login_id'] . "') OR (type = 2 AND users.group_id IN($group_User))   order by concat(firstname,' ',lastname) asc ");
                                while ($row = $managers->fetch_assoc()):
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($manager_id) && $manager_id == $row['id'] ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                    </div>
                <?php else: ?>
                    <input type="hidden" name="manager_id" value="<?php echo $_SESSION['login_id'] ?>">
                <?php endif; ?>
                <div class="form-group" style="width: 98%;    margin-right: 9px;>
                <label for=" class="control-label">اعضای تیم </label>
                <select class="form-control form-control-sm select2" multiple="multiple" name="user_ids[]">
                    <option></option>
                    <?php
                    if ( $_SESSION['login_type'] == 1)
                        $type = 2;
                    else
                        $type = 3;
                    $employees = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where (users.id =  '" . $_SESSION['login_id'] . "') OR (type = $type AND users.group_id IN($group_User))   order by concat(firstname,' ',lastname) asc ");
                    while ($row = $employees->fetch_assoc()):
                        ?>
                        <option value="<?php echo $row['id'] ?>" <?php echo isset($user_ids) && in_array($row['id'], explode(',', $user_ids)) ? "selected" : '' ?>><?php echo ucwords($row['name']) ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="" class="control-label">توضیحات</label>
                <textarea name="description" id="" cols="30" rows="10" class="summernote form-control">
						<?php echo isset($description) ? $description : '' ?>
					</textarea>
            </div>
        </div>
    </div>
    </form>
</div>
<div class="card-footer border-top border-info">
    <div class="d-flex w-100 justify-content-center align-items-center">
        <button class="btn btn-flat  bg-gradient-primary mx-2" form="manage-project" style="    border-radius: 0.25rem;">ذخیره</button>
        <button class="btn btn-flat bg-gradient-secondary mx-2" type="button" style="    border-radius: 0.25rem;"
                onclick="location.href='index.php?page=project_list'">لغو
        </button>
    </div>
</div>
</div>
</div>


<script>
    kamaDatepicker("start-date-id", {
        buttonsColor: "red",
        forceFarsiDigits: true,
        markToday: true,
        markHolidays: true,
        highlightSelectedDay: true,
        sync: true,
        gotoToday: true,
        placeholder: "تاریخ شروع پروژه را وارد نمایید"
    });
    kamaDatepicker("end-date-id", {
        buttonsColor: "red",
        forceFarsiDigits: true,
        markToday: true,
        markHolidays: true,
        highlightSelectedDay: true,
        sync: true,
        gotoToday: true,
        placeholder: "تاریخ سررسید پروژه را وارد نمایید"
    });

    $('#manage').click(function () {
        $('#manage').val("dojosij");
    });
    $('#manage-project').submit(function (e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_project',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function (resp) {
                if (resp == 1) {
                    alert_toast('داده ها با موفقیت ذخیره شدند', "success");
                    setTimeout(function () {
                        location.href = 'index.php?page=project_list'
                    }, 2000)
                }
            }
        })
    })
</script>