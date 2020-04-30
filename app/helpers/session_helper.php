<?php
function isLoggedIn()
    {
        if(session_status()==PHP_SESSION_NONE)
        {
            session_start();
        }
        if(isset($_SESSION['user_id']))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

function adminLoggedIn($user)
{
    if(session_status()==PHP_SESSION_NONE)
        {
            session_start();
        }
        
        if(isset($_SESSION['user_admin'])&&($_SESSION['user_id']==$user || $_SESSION['user_id']=='superadmin'))
        {
            
            return true;
        }
        else
        {
            return false;
        }
}
?>