import { Component } from '@angular/core';
import { Shell } from './app/core/layout/shell/shell';

@Component({
  selector: 'app-root',
  imports: [Shell],
  templateUrl: './app.html',
  styleUrl: './app.css',
})
export class App {}
