<?php require_once __DIR__.'/config.php';
if(!is_logged_in()) redirect('/cat_24rp08520/login.php');
$book_id=intval($_POST['book_id']??0);
if($book_id<=0){ redirect('/cat_24rp08520/books.php'); }
// Check availability
$stmt=$conn->prepare('SELECT status FROM books WHERE book_id=?');
$stmt->bind_param('i',$book_id);
$stmt->execute();
$book=$stmt->get_result()->fetch_assoc();
if(!$book){ redirect('/cat_24rp08520/books.php'); }
if($book['status']!=='available'){
  $_SESSION['flash']='Book is not available.';
  redirect('/cat_24rp08520/books.php');
}
// Borrow
$conn->begin_transaction();
try{
  $uid=$_SESSION['user']['id'];
  $stmt=$conn->prepare('INSERT INTO borrowed_books(book_id,user_id) VALUES(?,?)');
  $stmt->bind_param('ii',$book_id,$uid);
  $stmt->execute();
  $stmt=$conn->prepare('UPDATE books SET status="borrowed" WHERE book_id=?');
  $stmt->bind_param('i',$book_id);
  $stmt->execute();
  $conn->commit();
  $_SESSION['flash']='Borrowed successfully.';
}catch(Exception $e){
  $conn->rollback();
  $_SESSION['flash']='Error borrowing book.';
}
redirect('/cat_24rp08520/dashboard.php');
