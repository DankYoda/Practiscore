import { Component, signal } from '@angular/core';
import { RouterOutlet } from '@angular/router';
import {MatCard, MatCardActions, MatCardContent, MatCardHeader, MatCardTitle} from '@angular/material/card';

@Component({
  selector: 'app-root',
  imports: [RouterOutlet, MatCardHeader, MatCard, MatCardTitle, MatCardContent, MatCardActions],
  templateUrl: './app.html',
  styleUrl: './app.css'
})
export class App {
  protected readonly title = signal('client');
}
