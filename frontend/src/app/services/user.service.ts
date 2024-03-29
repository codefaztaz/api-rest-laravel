import { Injectable } from '@angular/core';
import {  HttpClient, HttpHeaders } from '@angular/common/http';
import { Observable } from 'rxjs';
import { User } from '../models/user';
import { global } from './global';

@Injectable({
  providedIn: 'root'
})
export class UserService {
  public url: string;
  public identity;
	public token;

  constructor(
    public _http:HttpClient
  ) {
      this.url = global.url;
     this.identity = this.getIdentity();
   }

  terst()
  {
    return "hola mundo desde un servicio";
  }

  register(user):Observable<any>
  {
    let json = JSON.stringify(user);

    console.log(json);
    let params = 'json='+json;
    
   
    console.log(params);

  let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
   // let headers = new HttpHeaders().set('Content-Type', 'application/json');
    
    return this._http.post(this.url+'register', params, {headers:headers});
  }

  signup(user, gettoken = null):Observable<any>
  {
      if(gettoken != null)
      {
        user.gettoken = 'true';
      }

      console.log(user);

      let json = JSON.stringify(user);
      let params = 'json='+json; 
      console.log(params);
      let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
      
      return this._http.post(this.url+'login', params, {headers:headers});     
  }

  update(token, user):Observable<any>
  {
    let json = JSON.stringify(user);
		let params = "json="+json;
    console.log(params);
  

		let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded')
									   .set('Authorization', token);

	   	return this._http.put(this.url + 'user/update', params, {headers: headers});
    
  }

  getUser(id):Observable<any>
  {
    

   // let json = JSON.stringify(user);
    let id_user= this.getIdentity();

    let params = id_user.id;
   // console.log(id_user);
    console.log(params);
    
    return this._http.get(this.url+'user/detail/' + params);     
  }

  getIdentity()
  {
		let identity = JSON.parse(localStorage.getItem('identity'));

		if(identity && identity != "undefined")
    {
			this.identity = identity;
		}
    else
    {

			this.identity = null;
		}

		return this.identity;
	}

	getToken()
  {
		let token = localStorage.getItem('token');

		if(token && token != "undefined")
    {
			this.token = token;
		}
    else{
      
			this.token = null;
		}

		return this.token;
	}
  
  
}