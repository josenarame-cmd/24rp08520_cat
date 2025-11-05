<?php require_once __DIR__.'/config.php';
if(!is_logged_in()) redirect('/cat_24rp08520/login.php');
$id=intval($_GET['id']??0);
if($id>0){
  $conn->begin_transaction();
  try{
    // find borrow record and book id
    $stmt=$conn->prepare('SELECT book_id FROM borrowed_books WHERE id=? AND user_id=? AND returned_at IS NULL');
    $stmt->bind_param('ii',$id,$_SESSION['user']['id']);
    $stmt->execute();
    $row=$stmt->get_result()->fetch_assoc();
    if($row){
      $book_id=$row['book_id'];
      $stmt=$conn->prepare('UPDATE borrowed_books SET returned_at=NOW() WHERE id=?');
      $stmt->bind_param('i',$id);
      $stmt->execute();
      $stmt=$conn->prepare('UPDATE books SET status="available" WHERE book_id=?');
      $stmt->bind_param('i',$book_id);
      $stmt->execute();
      $conn->commit();
    } else { $conn->rollback(); }
  }catch(Exception $e){ $conn->rollback(); }
}
redirect('/cat_24rp08520/dashboard.php');
