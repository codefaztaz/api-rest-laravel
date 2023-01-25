import { Component, OnInit, DoCheck } from '@angular/core';
import { UserService } from './services/user.service';
import { global } from './services/global';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit, DoCheck {
  title = 'frontend';
  public token;
	public identity;
  public url;

  constructor
  (
  	private _userService: UserService,
   
  )
  {

    this.url = global.url;
    this.loadUser();
  }

  ngOnInit()
  {
    console.log('Webapp cargada correctamente :)');
  
  }

  ngDoCheck()
  {
    this.loadUser();
  }

  loadUser()
  {
    this.identity = this._userService.getIdentity();
    this.token = this._userService.getToken();
  }

}
