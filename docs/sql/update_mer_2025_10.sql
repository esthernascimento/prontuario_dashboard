-- Atualizações de esquema conforme novo MER
-- Execute em ambiente de desenvolvimento antes de produção

-- Tabela: tbMedicamento
ALTER TABLE `tbMedicamento`
  ADD COLUMN `tipoMedicamento` VARCHAR(100) NULL AFTER `descMedicamento`,
  ADD COLUMN `nomeMedicamento` VARCHAR(255) NULL AFTER `tipoMedicamento`,
  ADD COLUMN `dosagemMedicamento` VARCHAR(100) NULL AFTER `nomeMedicamento`,
  ADD COLUMN `frequenciaMedicamento` VARCHAR(100) NULL AFTER `dosagemMedicamento`,
  ADD COLUMN `periodoMedicamento` VARCHAR(100) NULL AFTER `frequenciaMedicamento`;

-- Adiciona FK opcional para prontuário (compatível retroativamente)
ALTER TABLE `tbMedicamento`
  ADD COLUMN `idProntuarioFK` INT NULL AFTER `idPacienteFK`;

ALTER TABLE `tbMedicamento`
  ADD CONSTRAINT `fk_tbMedicamento_tbProntuario`
  FOREIGN KEY (`idProntuarioFK`) REFERENCES `tbProntuario`(`idProntuarioPK`)
  ON UPDATE CASCADE ON DELETE SET NULL;


-- Tabela: tbExame
ALTER TABLE `tbExame`
  ADD COLUMN `nomeExame` VARCHAR(255) NULL AFTER `idPacienteFK`,
  ADD COLUMN `tipoExame` VARCHAR(100) NULL AFTER `nomeExame`;

-- Garante unicidade por consulta + descrição + data
ALTER TABLE `tbExame`
  ADD UNIQUE KEY `uniq_exame_consulta_desc_data` (`idConsultaFK`, `descExame`, `dataExame`);


-- Tabela: tbAlergia
ALTER TABLE `tbAlergia`
  ADD COLUMN `nomeAlergia` VARCHAR(255) NULL AFTER `idPacienteFK`,
  ADD COLUMN `tipoAlergia` VARCHAR(100) NULL AFTER `nomeAlergia`,
  ADD COLUMN `severidadeAlergia` VARCHAR(50) NULL AFTER `tipoAlergia`;

-- Garante unicidade por paciente + descrição
ALTER TABLE `tbAlergia`
  ADD UNIQUE KEY `uniq_alergia_paciente_desc` (`idPacienteFK`, `descAlergia`);


-- Observações
-- - Ajuste os tipos (VARCHAR tamanhos) conforme necessidade do banco em produção.
-- - Caso as chaves/índices já existam, remova as linhas de criação de índices para evitar erros.
-- - Revise permissões de usuário para executar ALTER TABLE e CREATE INDEX.