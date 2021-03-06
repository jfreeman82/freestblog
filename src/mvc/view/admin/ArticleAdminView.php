<?php
namespace freest\blog\mvc\view\admin;

use freest\blog\mvc\view\admin\AdminView as AdminView;
use freest\blog\modules\articles\Article as Article;

/**
 * Description of ArticleView
 *
 * @author myrmidex
 */
class ArticleAdminView extends AdminView 
{
    public function articlesList(Array $articles) 
    {
        $this->title = 'Articles';
        $this->content = '
      <table class="table table-bordered">
        <tr class="row">
          <th class="col-lg-1">id</th>
          <th class="col-lg-1">date</th>
          <th class="col-lg-6">title</th>
          <th class="col-lg-2">user</th>
          <th class="col-lg-2">actions</th>
        </tr>';
    
        foreach ($articles as $article) {
            $this->content .= '
        <tr class="row">
          <td><a href="'.ADMIN_URL.'article/'. $article->id()  .'/">'.  $article->id()                               .'</a></td>
          <td><a href="'.ADMIN_URL.'article/'. $article->id()  .'/">'.  date("d/m/Y",strtotime($article->gendate())) .'</a></td>
          <td><a href="'.ADMIN_URL.'article/'. $article->id()  .'/">'.  $article->title()                            .'</a></td>
          <td><a href="'.ADMIN_URL.'user/'.    $article->uid() .'/">'.  $article->user()->username()                 .'</a></td>
          <td>
            <a href="'.ADMIN_URL.'article/'. $article->id()  .'/edit">  Edit  </a>&nbsp;
            <a href="'.ADMIN_URL.'article/'. $article->id()  .'/delete">Delete</a>
          </td>
        </tr>';      
        }
    
        $this->content .= ' 
      </table>
      <a href="'.ADMIN_URL.'article/new" class="btn btn-primary">Add New Article</a>';
        $this->dashboard();
    }
 
    
    public function article(Article $article) {
        $this->title = 'Article';
        $this->content = '
      <article>
        <h1>'.$article->getTitle().'</h1>
        <div class="art-info">posted on '.date("M d, Y", strtotime($article->getGenDate())).' by '.$article->user()->getUsername().'</div>
        <div class="art-body">'.$article->getArticle().'</div>
        <div class="art-buttons">
          <a href="'.ADMIN_URL.'article/'.$article->id().'/edit"    class="btn btn-warning">Edit  </a>
          <a href="'.ADMIN_URL.'article/'.$article->id().'/delete"  class="btn btn-danger"> Delete</a>
        </div>
      </article>';
        $this->dashboard();
    }
  
    public function article_newForm($warning = "") {
        $this->title = 'New Article';
        $this->base = 'dashboard';
        $this->content = '
      <div class="row">
        <div class="col-lg-8">';
        if ($warning != "") {
            $this->content .= '
        <div class="alert alert-danger">'.$warning.'</div>';
        }
        $this->content .= ' 
      <form action="'.ADMIN_URL.'article/new" method="POST">
        <div class="form-group">
          <label for="an_title">Title</label>
          <input type="text" name="an_title" id="an_title" class="form-control" placeholder="Title" required />
        </div>
        <div class="form-group">
          <label for="an_article">Article</label>
          <textarea name="an_article" id="an_article" class="form-control" placeholder="Article" required></textarea>
        </div>
        <input type="hidden" name="anform" value="go" />
        <input type="submit" value="Add Article" class="btn btn-primary"/>
      </form>
      </div>
      </div>';
        $this->dashboard();
    }
    
    public function article_editForm(Article $article, $warning = "") 
    {
        $this->title = 'Edit Article';
        $this->content = '
      <div class="row">
        <div class="col-lg-8">';
        if ($warning != "") {
            $this->content .= '
        <div class="alert alert-danger">'.$warning.'</div>';
        }
        $this->content .= ' 
      <form action="'.ADMIN_URL.'article/'.$article->id().'/edit" method="POST">
        <div class="form-group">
          <label for="ae_title">Title</label>
          <input type="text" name="ae_title" id="ae_title" class="form-control" value="'.$article->title().'"/>
        </div>
        <div class="form-group">
          <label for="ae_article">Article</label>
          <textarea name="ae_article" id="ae_article" class="form-control">'.
            $article->article().
          '</textarea>
        </div>
        <input type="hidden" name="aeform" value="go" />
        <input type="submit" value="Edit" class="btn btn-primary"/>
      </form>
      </div>
      </div>';
        $this->dashboard();
    }
    public function article_deleteForm(Article $article,$warning = "") {
        $this->title = 'Article';
        $this->content = '';
        if ($warning != "") {
            $this->content .= '
        <div class="alert alert-danger">'.$warning.'</div>';
        }
        $this->content .= ' 
      <article>
        <h1>'.$article->title().'</h1>
        <div class="art-info">posted on '.date("M d, Y", strtotime($article->genDate())).' by '.$article->user()->getUsername().'</div>
        <div class="art-body">'.$article->article().'</div>
        <form action="'.ADMIN_URL.'article/'.$article->id().'/delete" method="POST">
          <input type="hidden" name="adform" value="go" />
          <div class="alert alert-danger">Are you sure you want to delete this article?</div>
          <input type="submit" value="Yes, Delete" class="btn btn-danger"/>
        </form>
      </article>';
        $this->dashboard();
    }
    
    
  /* PAGE BLOCKS 
   * 
   *  can be reused multiple times per page
   */
  
  // ArticleBlock puts everything in an article and returns it
  private function articleBlock($art) {
    return '
          <article class="blog-post">
            <h2 class="blog-post-title">'.$art['title'].'</h2>
            <p class="blog-post-meta">'.date("F j, Y, g:i a",strtotime($art['gendate'])).' by <a href="#">'.$art['username'].'</a></p>
            <p class="blog-post-body">'.$art['article'].'</p>
          </article>';
  }
  
    
}
