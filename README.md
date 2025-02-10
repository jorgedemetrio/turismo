# Componente Turismo

## Descrição
O Componente Turismo é uma extensão do Joomla projetada para gerenciar funcionalidades relacionadas ao turismo. Ele fornece uma interface administrativa para gerenciar vários recursos, incluindo acomodações, atrações locais e interações com usuários.

## Funcionalidades
- Gerenciar diferentes tipos de acomodações.
- Lidar com atrações e recursos locais.
- Suporte multilíngue (Inglês, Espanhol, Português).
- Interface administrativa amigável.

## Instalação
1. Baixe a versão mais recente do Componente Turismo.
2. Faça login no painel administrativo do Joomla.
3. Navegue até `Extensões` > `Gerenciar` > `Instalar`.
4. Faça o upload do pacote baixado e clique em `Instalar`.
5. Após a instalação, configure as definições do componente conforme necessário.

## Uso
- Acesse o componente a partir do painel administrativo do Joomla.
- Use as visualizações fornecidas para gerenciar acomodações, atrações locais e outros recursos.
- Personalize as configurações do componente para atender às suas necessidades.

## Estrutura de Arquivos
```
com_turismo/
├── administrator/
│   ├── controllers/          # Controladores para gerenciar recursos
│   ├── models/               # Modelos para manipulação de dados
│   ├── views/                # Visualizações para a interface administrativa
│   ├── language/             # Arquivos de idioma para suporte multilíngue
│   ├── sql/                  # Scripts SQL para instalação e atualizações
│   └── src/                  # Arquivos de origem para a lógica do componente
├── site/
│   ├── controllers/          # Controladores para o front-end
│   ├── models/               # Modelos para manipulação de dados do front-end
│   └── views/                # Visualizações para a interface do front-end
├── sql/
│   ├── install.sql           # Script SQL para instalação inicial
│   └── uninstall.sql         # Script SQL para desinstalação
└── README.md                 # Este arquivo README
```

## Contribuindo
Contribuições são bem-vindas! Por favor, envie um pull request ou abra uma issue para quaisquer melhorias ou correções de bugs.

## Licença
Este projeto está licenciado sob a Licença MIT. Veja o arquivo LICENSE para detalhes.



# Sonar

```
sonar-scanner.bat `
  -D"sonar.organization=nome" `
  -D"sonar.projectKey=jorgedemetrio_turismo" `
  -D"sonar.sources=." `
  -D"sonar.host.url=https://sonarcloud.io"

```
